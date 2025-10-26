<x-layout title="Notes - Larafony Notes Pro+">
    <a href="/" class="back-link">← Back to Home</a>

    <h1>📝 Notes</h1>



    <h2 style="margin-bottom: 1.5rem; color: #2c3e50;">All Notes</h2>

    @if(empty($notes))
        <div style="background: white; padding: 40px; text-align: center; border-radius: 8px;">
            <p style="color: #888; font-size: 1.1rem;">No notes found</p>
            <p style="color: #aaa; margin-top: 10px;">Create your first note to get started!</p>
        </div>
    @else
        <div class="notes-grid">
            @foreach($notes as $note)
            <div class="note-card">
                <div class="note-title">{{ $note->title }}</div>
                <div class="note-content">{{ $note->content }}</div>
                <div class="note-meta">
                    <div>Author: {{ $note->user->name ?? 'Unknown' }}</div>
                    @if(!empty($note->tags))
                        <div style="margin-top: 8px;">
                            @foreach($note->tags as $tag)
                                <span class="tag">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-top: 2rem;">
        <h2 style="margin-bottom: 1rem; color: #2c3e50;">Create New Note</h2>
        <form method="POST" action="/notes">
            <input type="text" name="title" placeholder="Note title..." required style="width: 100%; padding: 0.8rem; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 4px;">
            <textarea name="content" placeholder="Write your note content..." rows="4" required style="width: 100%; padding: 0.8rem; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 4px;"></textarea>
            <input type="text" name="tags" placeholder="Tags (comma separated, e.g., php, framework, clean-code)" style="width: 100%; padding: 0.8rem; margin-bottom: 1rem; border: 1px solid #ddd; border-radius: 4px;">
            <button type="submit" style="background: #3498db; color: white; border: none; padding: 0.8rem 2rem; border-radius: 4px; cursor: pointer; font-weight: 600;">Add Note</button>
        </form>
    </div>
</x-layout>
