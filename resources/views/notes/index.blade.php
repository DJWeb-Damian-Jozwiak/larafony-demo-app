<x-Layout title="Larafony Notes Pro+">
    <h1>Larafony Notes </h1>

    @if(empty($notes))
        <p>No notes yet. Create your first note above!</p>
    @else
        @foreach($notes as $note)
            <article>
                <h2>{{ $note->title }}</h2>
                <p>{{ $note->content }}</p>
                <small>by {{ $note->user->name ?? 'Unknown' }}</small>

                @if(!empty($note->tags))
                    <div class="tags">
                        @foreach($note->tags as $tag)
                            <span class="tag">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                @endif

                <details>
                    <summary>Comments ({{ count($note->comments) }})</summary>

                    @foreach($note->comments as $comment)
                        <div class="comment">
                            <b>{{ $comment->author }}:</b> {{ $comment->content }}
                        </div>
                    @endforeach

                    <form method="POST" action="/comments">
                        <input type="hidden" name="note_id" value="{{ $note->id }}">
                        <input name="author" placeholder="Your name" required>
                        <input name="content" placeholder="Write a comment..." required>
                        <button type="submit">Send Comment</button>
                    </form>
                </details>
            </article>
        @endforeach
    @endif
<hr/>

    <form method="POST" action="/notes">
        <input name="title" placeholder="Note title..." required>
        <textarea name="content" placeholder="Write something amazing..." rows="4" required></textarea>
        <input name="tags" placeholder="Tags (comma separated, e.g., php, framework, clean-code)">
        <button type="submit">Add Note</button>
    </form>
</x-Layout>
