@extends('layouts.app')

@section('title', 'Mon Crush - Hear Me Out')

@section('content')
<h2 style="margin-bottom: 1.5rem;">Mon Crush</h2>

@if(!$crush)
    <div style="background: #f8f9fa; padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem;">Créer mon crush</h3>
        <form method="POST" action="{{ route('crush.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="title">Titre (optionnel)</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}">
            </div>

            <div class="form-group">
                <label for="text">Texte *</label>
                <textarea id="text" name="text" required>{{ old('text') }}</textarea>
            </div>

            <div class="form-group">
                <label for="image">Image (optionnel)</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" class="btn btn-success">Créer</button>
        </form>
    </div>
@else
    <div style="background: #f8f9fa; padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem;">Modifier mon crush</h3>
        <form method="POST" action="{{ route('crush.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" value="{{ old('title', $crush->title) }}">
            </div>

            <div class="form-group">
                <label for="text">Texte *</label>
                <textarea id="text" name="text" required>{{ old('text', $crush->text) }}</textarea>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                @if($crush->image_path)
                    <img src="{{ Storage::url($crush->image_path) }}" alt="Crush image" style="max-width: 200px; display: block; margin-bottom: 0.5rem; border-radius: 4px;">
                @endif
                <input type="file" id="image" name="image" accept="image/*">
                <small style="color: #666; display: block; margin-top: 0.25rem;">
                    Modifier l'image ou le titre réinitialisera les statistiques
                </small>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-success">Mettre à jour</button>
                <button type="button" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer votre crush ?')) document.getElementById('delete-form').submit();" class="btn btn-danger">
                    Supprimer
                </button>
            </div>
        </form>

        <form id="delete-form" method="POST" action="{{ route('crush.destroy') }}" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <div style="background: #fff; padding: 2rem; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 2rem;">
        <h3 style="margin-bottom: 1rem;">Statistiques</h3>
        @php
            $stats = $crush->getVoteStats();
            $total = array_sum($stats);
        @endphp
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
            <div style="text-align: center; padding: 1rem; background: #e8f5e9; border-radius: 4px;">
                <div style="font-size: 2rem; font-weight: bold; color: #2ecc71;">{{ $stats['oui'] }}</div>
                <div>Oui</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: #ffebee; border-radius: 4px;">
                <div style="font-size: 2rem; font-weight: bold; color: #e74c3c;">{{ $stats['non'] }}</div>
                <div>Non</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: #fff3e0; border-radius: 4px;">
                <div style="font-size: 2rem; font-weight: bold; color: #f39c12;">{{ $stats['non_tare'] }}</div>
                <div>Non, taré</div>
            </div>
            <div style="text-align: center; padding: 1rem; background: #f3e5f5; border-radius: 4px;">
                <div style="font-size: 2rem; font-weight: bold; color: #9b59b6;">{{ $stats['tare_mais_oui'] }}</div>
                <div>Taré mais oui</div>
            </div>
        </div>
        <p style="text-align: center; margin-top: 1rem; color: #666;">Total des votes: {{ $total }}</p>
    </div>

    <div style="background: #fff; padding: 2rem; border-radius: 8px; border: 1px solid #ddd;">
        <h3 style="margin-bottom: 1rem;">Commentaires ({{ $crush->comments->count() }})</h3>
        
        @forelse($crush->comments as $comment)
            <div style="border-bottom: 1px solid #eee; padding: 1rem 0;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <strong>{{ $comment->user->name }}</strong>
                    <small style="color: #666;">{{ $comment->created_at->diffForHumans() }}</small>
                </div>
                <p style="margin-bottom: 0.5rem;">{{ $comment->text }}</p>
                <div style="display: flex; gap: 1rem; font-size: 0.9rem; color: #666;">
                    <span>👍 {{ $comment->getLikesCount() }}</span>
                    <span>👎 {{ $comment->getDislikesCount() }}</span>
                </div>
            </div>
        @empty
            <p style="color: #666; text-align: center;">Aucun commentaire pour le moment</p>
        @endforelse
    </div>
@endif
@endsection
