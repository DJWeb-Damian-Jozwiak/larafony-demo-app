<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use App\DTOs\CreateNoteDto;
use Larafony\Framework\Auth\Auth;
use Larafony\Framework\Cache\Cache;
use Larafony\Framework\Database\Base\Query\Enums\OrderDirection;
use Larafony\Framework\Routing\Advanced\Attributes\Middleware;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[Middleware(beforeGlobal: [AuthMiddleware::class])]
class NoteController extends Controller
{
    public function __construct()
    {
        parent::__construct(\Larafony\Framework\Web\Application::instance());
    }

    #[Route('/notes', 'GET')]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        // Use cache for frequently accessed recent notes
        $cache = Cache::instance();

        $notes = $cache->remember(
            key: 'notes.recent',
            ttl: 600, // 10 minutes
            callback: fn() => Note::query()->orderBy('created_at', OrderDirection::DESC)
                ->limit(10)
                ->get()
        );

        // Get active tags from cache (warmed by cache:warm command)
        $activeTags = $cache->get('tags.active', []);

        // Relationships are lazy-loaded via attribute-based properties
        // Access $note->user, $note->tags, $note->comments directly in views

        return $this->render('notes.index', [
            'notes' => $notes,
            'activeTags' => $activeTags,
        ]);
    }

    #[Route('/notes', 'POST')]
    public function store(CreateNoteDto $dto): ResponseInterface
    {

        // DTO is automatically created and validated by FormRequestAwareHandler

        // Check if user is authenticated
        if (!Auth::check()) {
            return $this->json([
                'message' => 'Unauthorized',
                'errors' => ['auth' => ['You must be logged in to create notes.']]
            ], 401);
        }

        // Check if user has permission to create notes
        if (!Auth::hasPermission('notes.create')) {
            return $this->json([
                'message' => 'Forbidden',
                'errors' => ['permission' => ['You do not have permission to create notes.']]
            ], 403);
        }

        /** @var User $user */
        $user = Auth::user();

        // Create note
        $note = new Note()->fill([
            'title' => $dto->title,
            'content' => $dto->content,
            'user_id' => $user->id,
        ]);
        $note->save();

        // Attach tags if provided
        if ($dto->tags) {
            $tagIds = [];
            foreach ($dto->tags as $tagName) {
                $tagName = trim($tagName);
                if (empty($tagName)) {
                    continue;
                }

                // Find or create tag
                $tag = Tag::query()->where('name', '=', $tagName)->first();
                if (!$tag) {
                    $tag = new Tag()->fill(['name' => $tagName]);
                    $tag->save();
                }
                $tagIds[] = $tag->id;
            }

            // Attach all tags to note via pivot table
            if (!empty($tagIds)) {
                $note->attachTags($tagIds);
            }
        }

        // Invalidate cache after creating a note
        Cache::instance()->tags(['notes', 'statistics'])->flush();

        return $this->redirect('/notes');
    }
}
