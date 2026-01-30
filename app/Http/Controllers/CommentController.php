<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, News $news)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $comment = $news->comments()->create([
            'body' => $request->input('body'),
            'user_id' => auth()->id(),
            // By default, don't auto-approve user comments for moderation workflow
            'approved' => auth()->user()->hasAnyRole(['Super Admin', 'Editor', 'Moderator']) ? true : false,
        ]);
        // Notify moderators if comment is not auto-approved
        if (! $comment->approved) {
            $moderators = \App\Models\User::role(['Super Admin','Editor','Moderator'])->get();
            if ($moderators->count()) {
                \Illuminate\Support\Facades\Notification::send($moderators, new \App\Notifications\NewCommentForModeration($comment));
            }
        }
        return back()->with('success', 'Comment submitted' . ($comment->approved ? '' : ' and awaiting moderation'));
    }

    public function approve(Comment $comment)
    {
        $user = auth()->user();
        if (! $user || ! $user->hasAnyRole(['Super Admin', 'Editor', 'Moderator'])) {
            abort(403);
        }

        $comment->update(['approved' => true]);

        return back()->with('success', 'Comment approved.');
    }

    public function destroy(Comment $comment)
    {
        $user = auth()->user();
        if ($user && ($user->id === $comment->user_id || $user->hasAnyRole(['Super Admin', 'Editor', 'Moderator']))) {
            $comment->delete();
            return back()->with('success', 'Comment deleted.');
        }

        abort(403);
    }
}
