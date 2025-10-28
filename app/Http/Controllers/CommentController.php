<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\CommentReaction;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'crush_id' => 'required|exists:crushes,id',
            'text' => 'required|string|max:1000',
        ]);

        Comment::create([
            'crush_id' => $validated['crush_id'],
            'user_id' => auth()->id(),
            'text' => $validated['text'],
        ]);

        return back()->with('success', 'Commentaire ajouté avec succès!');
    }

    public function react(Request $request)
    {
        $validated = $request->validate([
            'comment_id' => 'required|exists:comments,id',
            'type' => 'required|in:like,dislike',
        ]);

        // Check if user already reacted to this comment
        $existingReaction = CommentReaction::where('comment_id', $validated['comment_id'])
            ->where('user_id', auth()->id())
            ->first();

        if ($existingReaction) {
            // If same type, remove it (toggle)
            if ($existingReaction->type === $validated['type']) {
                $existingReaction->delete();
            } else {
                // Update to new type
                $existingReaction->update(['type' => $validated['type']]);
            }
        } else {
            // Create new reaction
            CommentReaction::create([
                'comment_id' => $validated['comment_id'],
                'user_id' => auth()->id(),
                'type' => $validated['type'],
            ]);
        }

        return back();
    }
}
