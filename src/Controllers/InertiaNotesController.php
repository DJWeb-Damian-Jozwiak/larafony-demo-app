<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Models\Note;
use App\Models\Tag;
use App\Models\User;
use Larafony\Framework\Routing\Advanced\Attributes\Middleware;
use Larafony\Framework\Routing\Advanced\Attributes\Route;
use Larafony\Framework\Routing\Advanced\Attributes\RouteParam;
use Larafony\Framework\Web\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[Middleware(beforeGlobal: [AuthMiddleware::class])]
class InertiaNotesController extends Controller
{
    public function __construct()
    {
        parent::__construct(\Larafony\Framework\Web\Application::instance());
    }

    #[Route('/inertia/notes', 'GET')]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        $notes = Note::query()->get();

        // Transform notes to array with relationships
        $notesData = array_map(function ($note) {
            return [
                'id' => $note->id,
                'title' => $note->title,
                'content' => $note->content,
                'user' => [
                    'id' => $note->user->id ?? null,
                    'name' => $note->user->name ?? 'Unknown',
                    'email' => $note->user->email ?? null,
                ],
                'tags' => array_map(
                    fn($tag) => [
                        'id' => $tag->id,
                        'name' => $tag->name,
                    ],
                    $note->tags ?? []
                ),
                'created_at' => $note->created_at ?? null,
            ];
        }, $notes);

        return $this->inertia('Notes/Index', [
            'notes' => $notesData,
        ]);
    }

    #[Route('/inertia/notes/create', 'GET')]
    public function create(): ResponseInterface
    {
        // Get all available tags for the form
        $tags = Tag::query()->get();

        $tagsData = array_map(
            fn($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ],
            $tags
        );

        return $this->inertia('Notes/Create', [
            'tags' => $tagsData,
        ]);
    }

    #[Route('/inertia/notes/<note:\d>', 'GET')]
    #[RouteParam(name: 'note', bind: Note::class)]
    public function show(ServerRequestInterface $request, Note $note): ResponseInterface
    {
        $noteData = [
            'id' => $note->id,
            'title' => $note->title,
            'content' => $note->content,
            'user' => [
                'id' => $note->user->id ?? null,
                'name' => $note->user->name ?? 'Unknown',
                'email' => $note->user->email ?? null,
            ],
            'tags' => array_map(
                fn($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ],
                $note->tags ?? []
            ),
            'comments' => array_map(
                fn($comment) => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => [
                        'name' => $comment->user->name ?? 'Anonymous',
                    ],
                    'created_at' => $comment->created_at ?? null,
                ],
                $note->comments ?? []
            ),
            'created_at' => $note->created_at ?? null,
            'updated_at' => $note->updated_at ?? null,
        ];

        return $this->inertia('Notes/Show', [
            'note' => $noteData,
        ]);
    }
}
