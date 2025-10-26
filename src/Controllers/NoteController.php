<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use App\DTOs\CreateNoteDto;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NoteController extends Controller
{
    public function __construct()
    {
        parent::__construct(\Larafony\Framework\Web\Application::instance());
    }

    #[Route('/notes', 'GET')]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $notes = Note::query()->get();

        // Relationships are lazy-loaded via attribute-based properties
        // Access $note->user, $note->tags, $note->comments directly in views

        return $this->render('notes.index', ['notes' => $notes]);
    }

    #[Route('/notes', 'POST')]
    public function store(CreateNoteDto $dto): ResponseInterface
    {
        // DTO is automatically created and validated by FormRequestAwareHandler

        // Get first user as demo user
        $user = User::query()->first();

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

        return $this->redirect('/notes');
    }
}
