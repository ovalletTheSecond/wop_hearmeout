@extends('layouts.app')

@section('title', 'Administration - Hear Me Out')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="margin: 0;">Administration</h2>
        <a href="{{ route('admin.categories') }}" 
           style="padding: 0.75rem 1.5rem; background: #3498db; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; transition: background 0.2s;"
           onmouseover="this.style.background='#2980b9';"
           onmouseout="this.style.background='#3498db';">
            📁 Gérer les catégories
        </a>
    </div>

    <!-- Actions rapides -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
        <h3 style="margin: 0 0 0.5rem 0; color: white; font-size: 1.2rem;">⚡ Actions rapides</h3>
        <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 1.25rem; font-size: 0.95rem;">Outils pratiques pour gérer votre expérience</p>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <form method="POST" action="{{ route('admin.reset.seen') }}" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir réinitialiser tous vos crushes vus ? Vous pourrez à nouveau les voir dans votre fil.');">
                @csrf
                <button type="submit" class="btn-admin-action">
                    <span style="display: inline-block; margin-right: 0.5rem;">🔄</span>
                    <span>Réinitialiser mes crushes vus</span>
                </button>
            </form>
        </div>
    </div>

    <style>
        .btn-admin-action {
            padding: 0.875rem 1.75rem;
            font-size: 1rem;
            font-weight: 600;
            background: white;
            color: #667eea;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
        }

        .btn-admin-action::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(102, 126, 234, 0.1);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-admin-action:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-admin-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-admin-action:hover span:first-child {
            animation: rotate360 0.6s ease;
        }

        .btn-admin-action:active {
            transform: translateY(0);
        }

        @keyframes rotate360 {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Section Signalements -->
        <div class="reported-crushes">
            <h3 style="margin-bottom: 1rem;">Crushs signalés</h3>
            @foreach($reportedCrushes as $crush)
                <div class="crush-card" style="background: white; padding: 1rem; margin-bottom: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        @if($crush->user->profile_image_path)
                            <img src="{{ Storage::url($crush->user->profile_image_path) }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        @else
                            <img src="{{ asset('resources/views/img_website/profil_default.png') }}" alt="Default Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        @endif
                        <div>
                            <h4>{{ $crush->title ?? 'Sans titre' }}</h4>
                            <small>Par {{ $crush->user->name }} • {{ $crush->reports_count }} signalement(s)</small>
                        </div>
                    </div>

                    <p style="margin-bottom: 1rem;">{{ $crush->text }}</p>

                    @if($crush->image_path)
                        <img src="{{ Storage::url($crush->image_path) }}" alt="Crush image" style="max-width: 200px; margin-bottom: 1rem; border-radius: 4px;">
                    @endif

                    <div style="display: flex; gap: 0.5rem;">
                        <form action="{{ route('admin.crush.delete', $crush->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce crush ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                        <form action="{{ route('admin.crush.priority', $crush->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                {{ $crush->is_priority ? 'Retirer priorité' : 'Marquer prioritaire' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

            {{ $reportedCrushes->links() }}
        </div>

        <!-- Section Messages -->
        <div class="messages">
            <h3 style="margin-bottom: 1rem;">Messages non lus</h3>
            @foreach($unreadMessages as $message)
                <div class="message-card" style="background: white; padding: 1rem; margin-bottom: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                        @if($message->user->profile_image_path)
                            <img src="{{ Storage::url($message->user->profile_image_path) }}" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        @else
                            <img src="{{ asset('resources/views/img_website/profil_default.png') }}" alt="Default Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        @endif
                        <div>
                            <strong>{{ $message->user->name }}</strong>
                            <small style="display: block; color: #666;">{{ $message->created_at->diffForHumans() }}</small>
                        </div>
                    </div>

                    <p style="margin-bottom: 1rem;">{{ $message->content }}</p>

                    <form action="{{ route('admin.message.read', $message->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Marquer comme lu</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection