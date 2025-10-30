<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Crush;
use App\Models\Category;
use App\Http\Requests\StoreCrushRequest;

class AccountController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $crush = auth()->user()->crush;
        $categories = Category::orderBy('name')->get();
        return view('account.index', compact('crush', 'categories'));
    }

    public function store(StoreCrushRequest $request)
    {
        // Validation déjà gérée par StoreCrushRequest
        $validated = $request->validated();

        $crushData = [
            'user_id' => auth()->id(),
            'title' => strip_tags($validated['title'] ?? ''),
            'text' => strip_tags($validated['text']),
            'category_id' => $validated['category_id'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Sécurité : générer un nom de fichier unique et sécurisé
            $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
            $crushData['image_path'] = $image->storeAs('crushes', $filename, 'public');
        }

        $crush = Crush::create($crushData);

        // Attach multiple categories
        if (!empty($validated['categories'])) {
            $crush->categories()->attach($validated['categories']);
        }

        return back()->with('success', 'Crush créé avec succès!');
    }

    public function update(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        $crush = auth()->user()->crush;

        if (!$crush) {
            return back()->withErrors(['error' => 'Vous n\'avez pas de crush.']);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:' . config('content.crush.title_max', 100),
            'text' => 'required|string|max:' . config('content.crush.text_max', 1000),
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:' . (config('content.crush.image_max_mb', 3) * 1024) . '|dimensions:max_width=4096,max_height=4096',
        ]);

        $updateData = [
            'text' => strip_tags($validated['text']),
        ];

        // Check if title or image changed (reset stats)
        $resetStats = false;

        if ($request->input('title') !== $crush->title) {
            $updateData['title'] = strip_tags($validated['title'] ?? '');
            $resetStats = true;
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($crush->image_path) {
                Storage::disk('public')->delete($crush->image_path);
            }
            
            $image = $request->file('image');
            $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
            $updateData['image_path'] = $image->storeAs('crushes', $filename, 'public');
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
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
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
