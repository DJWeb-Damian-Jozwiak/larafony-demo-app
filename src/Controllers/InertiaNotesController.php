<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Models\Note;
use App\Models\Tag;
use App\ViewDto\NoteViewDto;
use App\ViewDto\TagViewDto;
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
        $notesData = array_map(
            fn(NoteViewDto $dto) => $dto->toArray(),
            NoteViewDto::collection($notes)
        );

        return $this->inertia('Notes/Index', [
            'notes' => $notesData,
        ]);
    }

    #[Route('/inertia/notes/create', 'GET')]
    public function create(): ResponseInterface
    {
        $tags = Tag::query()->get();
        $tagsData = array_map(
            fn(TagViewDto $dto) => $dto->toArray(),
            TagViewDto::collection($tags)
        );

        return $this->inertia('Notes/Create', [
            'tags' => $tagsData,
        ]);
    }

    #[Route('/inertia/notes/<note:\d>', 'GET')]
    #[RouteParam(name: 'note', bind: Note::class)]
    public function show(ServerRequestInterface $request, Note $note): ResponseInterface
    {
        $noteDto = NoteViewDto::fromModel($note, withComments: true);

        return $this->inertia('Notes/Show', [
            'note' => $noteDto->toArray(),
        ]);
    }
}
