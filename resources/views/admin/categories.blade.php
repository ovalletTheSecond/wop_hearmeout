@extends('layouts.app')

@section('title', 'Gestion des catégories - Admin')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <h2 style="margin-bottom: 2rem;">Gestion des catégories</h2>

    @if(session('success'))
        <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Créer nouvelle catégorie -->
    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem;">Créer une nouvelle catégorie</h3>
        <form method="POST" action="{{ route('admin.categories.store') }}" style="display: flex; gap: 1rem; align-items: end;">
            @csrf
            <div style="flex: 1;">
                <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nom de la catégorie</label>
                <input type="text" id="name" name="name" required 
                       style="width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem;">
            </div>
            <button type="submit" 
                    style="padding: 0.75rem 1.5rem; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; font-weight: 600;">
                Créer
            </button>
        </form>
    </div>

    <!-- Liste des catégories -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #2c3e50; color: white;">
                <tr>
                    <th style="padding: 1rem; text-align: left;">ID</th>
                    <th style="padding: 1rem; text-align: left;">Nom</th>
                    <th style="padding: 1rem; text-align: left;">Slug</th>
                    <th style="padding: 1rem; text-align: center;">Crushs</th>
                    <th style="padding: 1rem; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr style="border-bottom: 1px solid #e9ecef;">
                        <td style="padding: 1rem;">{{ $category->id }}</td>
                        <td style="padding: 1rem;">
                            <form method="POST" action="{{ route('admin.categories.update', $category->id) }}" 
                                  id="update-form-{{ $category->id }}" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $category->name }}" 
                                       style="padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; width: 250px;">
                            </form>
                        </td>
                        <td style="padding: 1rem; color: #6c757d;">{{ $category->slug }}</td>
                        <td style="padding: 1rem; text-align: center;">
                            <span style="background: #e9ecef; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem;">
                                {{ $category->crushes()->count() }}
                            </span>
                        </td>
                        <td style="padding: 1rem; text-align: center;">
                            <button onclick="document.getElementById('update-form-{{ $category->id }}').submit()"
                                    style="padding: 0.5rem 1rem; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 0.5rem;">
                                Mettre à jour
                            </button>
                            <form method="POST" action="{{ route('admin.categories.delete', $category->id) }}" 
                                  style="display: inline;"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        style="padding: 0.5rem 1rem; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        {{ $categories->links() }}
    </div>

    <div style="margin-top: 2rem;">
        <a href="{{ route('admin.index') }}" style="color: #007bff; text-decoration: none;">
            ← Retour au tableau de bord admin
        </a>
    </div>
</div>
@endsection
