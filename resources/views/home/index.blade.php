@extends('layouts.app')

@section('title', 'Hear Me Out')

@push('styles')
<style>
    .crush-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .crush-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .crush-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        position: relative;
    }

    .crush-title {
        position: absolute;
        top: 1rem;
        left: 1rem;
        right: 1rem;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 1rem;
        border-radius: 4px;
        font-size: 1.5rem;
        font-weight: bold;
    }

    .crush-text {
        padding: 1.5rem;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    .vote-buttons {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        padding: 1.5rem;
        background: #f8f9fa;
    }

    .vote-btn {
        padding: 1rem;
        font-size: 1rem;
        font-weight: bold;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .vote-btn:hover {
        transform: scale(1.05);
    }

    .vote-oui {
        background: #2ecc71;
        color: white;
    }

    .vote-non {
        background: #e74c3c;
        color: white;
    }

    .vote-non-tare {
        background: #f39c12;
        color: white;
    }

    .vote-tare-mais-oui {
        background: #9b59b6;
        color: white;
    }

    .stats-container {
        background: white;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-box {
        text-align: center;
        padding: 1rem;
        border-radius: 4px;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        padding: 0 1.5rem 1.5rem;
        background: #f8f9fa;
    }

    .comment-section {
        background: white;
        border-radius: 8px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }

    .comment {
        border-bottom: 1px solid #eee;
        padding: 1rem 0;
    }

    .comment:last-child {
        border-bottom: none;
    }

    .comment-actions {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .reaction-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        transition: background 0.2s;
    }

    .reaction-btn:hover {
        background: #f0f0f0;
    }

    .reaction-btn.active {
        background: #e0e0e0;
    }
</style>
@endpush

@section('content')
<div class="crush-container">
    @if($crush)
        @if(!$hasVoted)
            <div class="crush-card">
                <div style="position: relative;">
                    @if($crush->image_path)
                        <img src="{{ Storage::url($crush->image_path) }}" alt="Crush" class="crush-image">
                    @else
                        <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                    @endif
                    @if($crush->title)
                        <div class="crush-title">{{ $crush->title }}</div>
                    @endif
                </div>
                <div class="crush-text">
                    {{ $crush->text }}
                </div>
                <form method="POST" action="{{ route('vote.submit') }}">
                    @csrf
                    <input type="hidden" name="crush_id" value="{{ $crush->id }}">
                    <div class="vote-buttons">
                        <button type="submit" name="vote_type" value="oui" class="vote-btn vote-oui">
                            ✓ Oui
                        </button>
                        <button type="submit" name="vote_type" value="non" class="vote-btn vote-non">
                            ✗ Non
                        </button>
                        <button type="submit" name="vote_type" value="non_tare" class="vote-btn vote-non-tare">
                            ⚠ Non, taré
                        </button>
                        <button type="submit" name="vote_type" value="tare_mais_oui" class="vote-btn vote-tare-mais-oui">
                            ⚡ Taré mais oui
                        </button>
                    </div>
                </form>
                <div class="action-buttons">
                    <button onclick="shareThis()" class="btn btn-secondary">
                        Partager
                    </button>
                    <button onclick="reportThis()" class="btn btn-danger">
                        Signaler
                    </button>
                </div>
            </div>
        @else
            <div class="stats-container">
                <h3 style="margin-bottom: 1.5rem; text-align: center;">Résultats du vote</h3>
                @php
                    $stats = $crush->getVoteStats();
                    $total = array_sum($stats);
                @endphp
                <div class="stats-grid">
                    <div class="stat-box" style="background: #e8f5e9;">
                        <div class="stat-number" style="color: #2ecc71;">{{ $stats['oui'] }}</div>
                        <div>Oui</div>
                        @if($total > 0)
                            <small>{{ round($stats['oui'] / $total * 100) }}%</small>
                        @endif
                    </div>
                    <div class="stat-box" style="background: #ffebee;">
                        <div class="stat-number" style="color: #e74c3c;">{{ $stats['non'] }}</div>
                        <div>Non</div>
                        @if($total > 0)
                            <small>{{ round($stats['non'] / $total * 100) }}%</small>
                        @endif
                    </div>
                    <div class="stat-box" style="background: #fff3e0;">
                        <div class="stat-number" style="color: #f39c12;">{{ $stats['non_tare'] }}</div>
                        <div>Non, taré</div>
                        @if($total > 0)
                            <small>{{ round($stats['non_tare'] / $total * 100) }}%</small>
                        @endif
                    </div>
                    <div class="stat-box" style="background: #f3e5f5;">
                        <div class="stat-number" style="color: #9b59b6;">{{ $stats['tare_mais_oui'] }}</div>
                        <div>Taré mais oui</div>
                        @if($total > 0)
                            <small>{{ round($stats['tare_mais_oui'] / $total * 100) }}%</small>
                        @endif
                    </div>
                </div>
                <p style="text-align: center; color: #666; font-size: 1.1rem;">
                    Total des votes: {{ $total }}
                </p>
            </div>

            <div class="comment-section">
                <h3 style="margin-bottom: 1rem;">Commentaires ({{ $crush->comments->count() }})</h3>
                
                @auth
                    <form method="POST" action="{{ route('comment.store') }}" style="margin-bottom: 2rem;">
                        @csrf
                        <input type="hidden" name="crush_id" value="{{ $crush->id }}">
                        <div class="form-group">
                            <textarea name="text" placeholder="Ajouter un commentaire..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Commenter</button>
                    </form>
                @else
                    <p style="text-align: center; color: #666; margin-bottom: 2rem;">
                        <a href="{{ route('login') }}">Connectez-vous</a> pour commenter
                    </p>
                @endauth

                @foreach($crush->comments as $comment)
                    <div class="comment">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                            <strong>{{ $comment->user->name }}</strong>
                            <small style="color: #666;">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <p>{{ $comment->text }}</p>
                        @auth
                            <div class="comment-actions">
                                <form method="POST" action="{{ route('comment.react') }}" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                    <input type="hidden" name="type" value="like">
                                    <button type="submit" class="reaction-btn">
                                        👍 {{ $comment->getLikesCount() }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('comment.react') }}" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                    <input type="hidden" name="type" value="dislike">
                                    <button type="submit" class="reaction-btn">
                                        👎 {{ $comment->getDislikesCount() }}
                                    </button>
                                </form>
                            </div>
                        @else
                            <div style="display: flex; gap: 1rem; margin-top: 0.5rem; color: #666; font-size: 0.9rem;">
                                <span>👍 {{ $comment->getLikesCount() }}</span>
                                <span>👎 {{ $comment->getDislikesCount() }}</span>
                            </div>
                        @endauth
                    </div>
                @endforeach
            </div>

            <div style="text-align: center;">
                <a href="{{ route('home') }}" class="btn">Voir un autre crush</a>
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 3rem; background: white; border-radius: 8px;">
            <h2 style="margin-bottom: 1rem;">Aucun crush disponible</h2>
            <p style="color: #666; margin-bottom: 1.5rem;">Il n'y a pas encore de crush à découvrir.</p>
            @auth
                <a href="{{ route('account') }}" class="btn btn-success">Créer mon crush</a>
            @else
                <a href="{{ route('login') }}" class="btn">Se connecter pour créer un crush</a>
            @endauth
        </div>
    @endif
</div>

@push('scripts')
<script>
function shareThis() {
    if (navigator.share) {
        navigator.share({
            title: 'Hear Me Out',
            text: 'Découvre ce crush!',
            url: window.location.href
        });
    } else {
        // Fallback
        navigator.clipboard.writeText(window.location.href);
        alert('Lien copié dans le presse-papier!');
    }
}

function reportThis() {
    const reason = prompt('Raison du signalement:');
    if (reason) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("report.submit") }}';
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        const crushId = document.createElement('input');
        crushId.type = 'hidden';
        crushId.name = 'crush_id';
        crushId.value = '{{ $crush?->id }}';
        form.appendChild(crushId);
        
        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reason';
        reasonInput.value = reason;
        form.appendChild(reasonInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection
