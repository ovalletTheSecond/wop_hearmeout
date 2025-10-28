<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crush;
use App\Models\Vote;
use App\Models\Report;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get a random crush
        $crush = Crush::inRandomOrder()->first();

        // Check if user has voted for this crush (using session)
        $hasVoted = false;
        if ($crush) {
            $sessionKey = 'voted_crush_' . $crush->id;
            $hasVoted = $request->session()->has($sessionKey);
        }

        return view('home.index', compact('crush', 'hasVoted'));
    }

    public function vote(Request $request)
    {
        $validated = $request->validate([
            'crush_id' => 'required|exists:crushes,id',
            'vote_type' => 'required|in:oui,non,non_tare,tare_mais_oui',
        ]);

        $crush = Crush::findOrFail($validated['crush_id']);

        // Record the vote
        Vote::create([
            'crush_id' => $crush->id,
            'vote_type' => $validated['vote_type'],
            'ip_address' => $request->ip(),
            'session_id' => $request->session()->getId(),
            'stats_version' => $crush->stats_version,
        ]);

        // Mark as voted in session
        $sessionKey = 'voted_crush_' . $crush->id;
        $request->session()->put($sessionKey, true);

        return back();
    }

    public function report(Request $request)
    {
        $validated = $request->validate([
            'crush_id' => 'required|exists:crushes,id',
            'reason' => 'nullable|string',
        ]);

        Report::create([
            'crush_id' => $validated['crush_id'],
            'ip_address' => $request->ip(),
            'reason' => $validated['reason'],
        ]);

        return back()->with('success', 'Signalement envoyé avec succès.');
    }
}
