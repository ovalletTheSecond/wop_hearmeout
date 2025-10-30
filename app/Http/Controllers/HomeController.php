<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Crush;
use App\Models\Vote;
use App\Models\Report;
use App\Models\Category;
use App\Services\SeenCrushService;

class HomeController extends Controller
{
    protected $seenCrushService;

    public function __construct(SeenCrushService $seenCrushService)
    {
        $this->seenCrushService = $seenCrushService;
    }

    public function index(Request $request)
    {
        // If the visitor hasn't seen the intro/landing page yet, show it first
        if (!$request->session()->has('intro_seen')) {
            return redirect()->route('intro');
        }
        // Build the query for random crush
        $query = Crush::query()->with(['categories'])->inRandomOrder();
        
        // Filter by category if provided
        if ($request->has('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->whereHas('categories', function($q) use ($category) {
                    $q->where('categories.id', $category->id);
                });
            }
        }
        
        // Exclude seen crushes if not in dev mode
        $excludeSeen = $this->seenCrushService->shouldExcludeSeen();
        if ($excludeSeen) {
            $seenCrushIds = $this->seenCrushService->getSeenCrushes();
            if (!empty($seenCrushIds)) {
                $query->whereNotIn('id', $seenCrushIds);
            }
        }

        // Get a random crush
        $crush = $query->first();

        // Calculate allCrushesViewed - check if all crushes have been seen
        $allCrushesViewed = false;
        if ($excludeSeen) {
            $seenCrushIds = $this->seenCrushService->getSeenCrushes();
            if (!empty($seenCrushIds)) {
                // Count total crushes (with category filter if applicable)
                $totalCrushesQuery = Crush::query();
                if ($request->has('category')) {
                    $category = Category::where('slug', $request->category)->first();
                    if ($category) {
                        $totalCrushesQuery->whereHas('categories', function($q) use ($category) {
                            $q->where('categories.id', $category->id);
                        });
                    }
                }
                $totalCrushes = $totalCrushesQuery->count();
                
                // If all crushes have been viewed (including the current one if voted)
                if ($totalCrushes > 0 && count($seenCrushIds) >= $totalCrushes) {
                    $allCrushesViewed = true;
                }
            }
        }

        // Check if user has voted for this crush (using session)
        $hasVoted = false;
        $userVoteType = null;
        $userHasCommented = false;
        
        if ($crush) {
            $sessionKey = 'voted_crush_' . $crush->id;
            $hasVoted = $request->session()->has($sessionKey);
            
            if ($hasVoted) {
                $userVoteType = $request->session()->get($sessionKey);
            }
            
            // Check if user has already commented on this crush
            if (auth()->check()) {
                $userHasCommented = $crush->comments()->where('user_id', auth()->id())->exists();
            }
            
            // Mark crush as seen when it's displayed (for skip functionality)
            if (!$hasVoted) {
                $this->seenCrushService->markAsSeen($crush->id);
            }
        }

        return view('home.index', compact('crush', 'hasVoted', 'userVoteType', 'userHasCommented', 'allCrushesViewed'));
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

        // Mark as voted in session (store the vote type)
        $sessionKey = 'voted_crush_' . $crush->id;
        $request->session()->put($sessionKey, $validated['vote_type']);

        // Mark crush as seen
        $this->seenCrushService->markAsSeen($crush->id);

        // Return stats as JSON if AJAX request
        if ($request->expectsJson() || $request->ajax()) {
            $stats = $crush->getVoteStats();
            $total = array_sum($stats);
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'total' => $total
            ]);
        }

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

    public function resetSeen(Request $request)
    {
        $this->seenCrushService->clearSeenCrushes();
        return redirect()->route('home')->with('success', 'Les crushes vus ont été réinitialisés !');
    }
}
