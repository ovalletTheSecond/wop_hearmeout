<?php

namespace App\Http\Controllers;

use App\Models\Crush;
use App\Models\Message;
use App\Models\Report;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $reportedCrushes = Crush::where('reports_count', '>=', 5)
            ->orWhere('is_priority', true)
            ->with(['reports', 'user'])
            ->orderBy('reports_count', 'desc')
            ->paginate(10);

        $unreadMessages = Message::with('user')
            ->where('read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.index', compact('reportedCrushes', 'unreadMessages'));
    }

    public function deleteCrush($id)
    {
        $crush = Crush::findOrFail($id);
        
        // Supprimer l'image si elle existe
        if ($crush->image_path) {
            Storage::delete($crush->image_path);
        }

        $crush->delete();
        
        return back()->with('success', 'Crush supprimé avec succès.');
    }

    public function markMessageAsRead($id)
    {
        Message::findOrFail($id)->update(['read' => true]);
        return back()->with('success', 'Message marqué comme lu.');
    }

    public function togglePriority($id)
    {
        $crush = Crush::findOrFail($id);
        $crush->update(['is_priority' => !$crush->is_priority]);
        return back()->with('success', 'Statut prioritaire mis à jour.');
    }

    // Gestion des catégories
    public function categories()
    {
        $categories = Category::orderBy('name')->paginate(20);
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', 'Catégorie créée avec succès.');
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return back()->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Set crushes to null category before deleting
        Crush::where('category_id', $id)->update(['category_id' => null]);
        
        $category->delete();
        
        return back()->with('success', 'Catégorie supprimée avec succès.');
    }

    public function resetSeenCrushes(Request $request)
    {
        // Clear seen crushes for admin only (personal reset)
        app(\App\Services\SeenCrushService::class)->clearSeenCrushes();
        
        return back()->with('success', 'Vos crushes vus ont été réinitialisés !');
    }
}