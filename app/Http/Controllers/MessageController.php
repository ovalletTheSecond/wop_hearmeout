<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:' . config('content.message.max', 2000) . '|min:10'
        ]);

        Message::create([
            'user_id' => auth()->id(),
            'content' => strip_tags($validated['content'])
        ]);

        return back()->with('success', 'Message envoyé avec succès.');
    }
}