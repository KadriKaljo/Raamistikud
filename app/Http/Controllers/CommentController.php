<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommentController extends Controller
{
    /**
     * Administraatori: kõik kommentaarid (kustutamise võimalusega).
     */
    public function adminIndex(Request $request)
    {
        if (! ($request->user()?->is_admin)) {
            abort(403);
        }

        return Inertia::render('admin/Comments', [
            'comments' => Comment::query()
                ->with(['post:id,title', 'user:id,name,email'])
                ->latest()
                ->paginate(30),
        ]);
    }

    public function store(Post $post, Request $request)
    {
        $request->validate([
            'content' => 'required|max:2000',
        ]);

        $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        return redirect()->back();
    }

    public function destroy(Comment $comment, Request $request)
    {
        $user = $request->user();
        if (! $user) {
            abort(403);
        }

        $canDelete = $user->is_admin || $user->id === $comment->user_id;
        if (! $canDelete) {
            abort(403, 'Unauthorized action.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Kommentaar kustutatud.');
    }
}
