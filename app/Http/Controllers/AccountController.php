<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Crush;

class AccountController extends Controller
{
    public function index()
    {
        $crush = auth()->user()->crush;
        return view('account.index', compact('crush'));
    }

    public function store(Request $request)
    {
        // Check if user already has a crush
        if (auth()->user()->crush) {
            return back()->withErrors(['error' => 'Vous avez déjà un crush.']);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'text' => 'required|string',
            'image' => 'nullable|image|max:10240',
        ]);

        $crushData = [
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'text' => $validated['text'],
        ];

        if ($request->hasFile('image')) {
            $crushData['image_path'] = $request->file('image')->store('crushes', 'public');
        }

        Crush::create($crushData);

        return back()->with('success', 'Crush créé avec succès!');
    }

    public function update(Request $request)
    {
        $crush = auth()->user()->crush;

        if (!$crush) {
            return back()->withErrors(['error' => 'Vous n\'avez pas de crush.']);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'text' => 'required|string',
            'image' => 'nullable|image|max:10240',
        ]);

        $updateData = [
            'text' => $validated['text'],
        ];

        // Check if title or image changed (reset stats)
        $resetStats = false;

        if ($request->input('title') !== $crush->title) {
            $updateData['title'] = $validated['title'];
            $resetStats = true;
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($crush->image_path) {
                Storage::disk('public')->delete($crush->image_path);
            }
            $updateData['image_path'] = $request->file('image')->store('crushes', 'public');
            $resetStats = true;
        }

        // If stats need to be reset, increment stats version
        if ($resetStats) {
            $updateData['stats_version'] = $crush->stats_version + 1;
        }

        $crush->update($updateData);

        return back()->with('success', 'Crush mis à jour avec succès!');
    }

    public function destroy()
    {
        $crush = auth()->user()->crush;

        if (!$crush) {
            return back()->withErrors(['error' => 'Vous n\'avez pas de crush.']);
        }

        // Delete image if exists
        if ($crush->image_path) {
            Storage::disk('public')->delete($crush->image_path);
        }

        $crush->delete();

        return back()->with('success', 'Crush supprimé avec succès!');
    }
}
