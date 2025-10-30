@extends('layouts.app')

@section('title', 'Hear Me Out')

@php
    $commentMax = config('content.comment.max', 500);
@endphp

@push('styles')
<link href="{{ asset('css/crush.css') }}" rel="stylesheet">
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .crush-container {
        max-width: 700px;
        margin: 0 auto;
        animation: fadeInUp 0.6s ease-out;
    }

    .crush-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .crush-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    }

    .crush-image-wrapper {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .crush-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .crush-card:hover .crush-image {
        transform: scale(1.05);
    }

    .crush-title {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 100%);
        color: white;
        padding: 2rem 1.5rem 1.5rem;
        font-size: 1.75rem;
        font-weight: 700;
        animation: slideIn 0.5s ease-out 0.2s both;
    }

    .crush-text {
        padding: 2rem;
        font-size: 1.15rem;
        line-height: 1.8;
        color: #2c3e50;
        animation: fadeInUp 0.6s ease-out 0.3s both;
    }

    .vote-buttons {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .vote-btn {
        padding: 1.25rem;
        font-size: 1.1rem;
        font-weight: 700;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }

    .vote-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .vote-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .vote-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }

    .vote-btn:active {
        transform: translateY(0);
    }

    .vote-oui {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        color: white;
    }

    .vote-non {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
    }

    .vote-non-tare {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        color: white;
    }

    .vote-tare-mais-oui {
        background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        color: white;
    }

    .stats-container {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease-out 0.4s both;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .stat-box {
        text-align: center;
        padding: 1.5rem 1rem;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }

    .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        animation: pulse 2s ease-in-out infinite;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        padding: 0 1.5rem 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .btn {
        flex: 1;
        padding: 0.875rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        color: white;
    }

    .category-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0.25rem;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
        animation: fadeInUp 0.6s ease-out 0.5s both;
    }

    .no-crush-message {
        text-align: center;
        padding: 4rem 2rem;
        animation: fadeInUp 0.6s ease-out;
    }

    .no-crush-message h3 {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: #2c3e50;
    }

    .skip-btn {
        display: inline-block;
        padding: 1rem 2.5rem;
        background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        color: white;
        text-decoration: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.05rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-top: 1.5rem;
    }

    .skip-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        background: linear-gradient(135deg, #7f8c8d 0%, #6c7a7b 100%);
    }

    .comment-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease-out 0.6s both;
    }

    .comment {
        border-bottom: 1px solid #eee;
        padding: 1.5rem 0;
        animation: slideIn 0.5s ease-out;
    }

    .comment:last-child {
        border-bottom: none;
    }

    .comment-actions {
        display: flex;
        gap: 1rem;
        margin-top: 0.75rem;
    }

    .reaction-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
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

    .crush-text-subtitle {
        font-weight: bold;
        margin-bottom: 0.5rem;
        margin-left : 1.5rem;
    }

    /* Modal styles */
    .stats-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        z-index: 9999;
        justify-content: center;
        align-items: center;
        animation: fadeIn 0.3s ease;
    }

    .stats-modal.show {
        display: flex;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideInScale {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .stats-modal-content {
        background: white;
        border-radius: 20px;
        padding: 2.5rem;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        animation: slideInScale 0.4s ease;
        position: relative;
    }

    .stats-modal-close {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: #f0f0f0;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        font-size: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stats-modal-close:hover {
        background: #e0e0e0;
        transform: rotate(90deg);
    }

    .stats-modal-title {
        font-size: 2rem;
        font-weight: 800;
        text-align: center;
        margin-bottom: 2rem;
        color: #2c3e50;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Loading animation */
    .loading-spinner {
        display: inline-block;
        width: 60px;
        height: 60px;
        border: 6px solid #f3f3f3;
        border-top: 6px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 2rem auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loading-container {
        text-align: center;
        padding: 3rem 2rem;
        transition: opacity 0.3s ease;
    }

    .loading-container.fade-out {
        opacity: 0;
    }

    .loading-text {
        margin-top: 1rem;
        font-size: 1.1rem;
        color: #666;
        font-weight: 600;
    }

    .loading-dots::after {
        content: '';
        animation: dots 1.5s steps(4, end) infinite;
    }

    @keyframes dots {
        0%, 20% { content: ''; }
        40% { content: '.'; }
        60% { content: '..'; }
        80%, 100% { content: '...'; }
    }

    .stats-content {
        display: none;
        animation: fadeInUp 0.5s ease-out;
    }

    .stats-content.show {
        display: block;
    }

    .btn-reset-seen {
        padding: 1rem 2rem;
        font-size: 1.1rem;
        background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        color: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(155, 89, 182, 0.3);
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-reset-seen::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .btn-reset-seen:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-reset-seen:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(155, 89, 182, 0.4);
    }
</style>
@endpush

@section('content')
@if($allCrushesViewed)
    <!-- Page personnalisée quand tous les crushes sont vus -->
    <div style="text-align: center; padding: 4rem 2rem; max-width: 700px; margin: 0 auto;">
        <div style="font-size: 8rem; margin-bottom: 2rem; animation: pulse 2s ease-in-out infinite;">🎉</div>
        <h1 style="font-size: 2.5rem; color: #2c3e50; margin-bottom: 1.5rem; font-weight: 700;">
            Félicitations !
        </h1>
        <h2 style="font-size: 1.5rem; color: #7f8c8d; margin-bottom: 2rem; font-weight: 400;">
            Vous avez découvert tous les crushes disponibles
        </h2>
        <p style="color: #95a5a6; font-size: 1.2rem; line-height: 1.8; margin-bottom: 3rem; max-width: 500px; margin-left: auto; margin-right: auto;">
            Impressionnant ! Vous avez exploré chaque crush de notre communauté. 
            Revenez bientôt pour de nouveaux contenus ou recommencez l'aventure depuis le début !
        </p>
        
        <div style="display: flex; gap: 1.5rem; justify-content: center; flex-wrap: wrap; margin-bottom: 3rem;">
            <form method="POST" action="{{ route('reset.seen') }}" style="display: inline-block;">
                @csrf
                <button type="submit" class="btn-reset-seen" style="padding: 1.2rem 2.5rem; font-size: 1.2rem; border-radius: 15px;">
                    🔄 Recommencer l'exploration
                </button>
            </form>
            
            @auth
                <a href="{{ route('account') }}" class="btn btn-success" 
                   style="padding: 1.2rem 2.5rem; font-size: 1.2rem; background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white; text-decoration: none; border-radius: 15px; display: inline-block; box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3); transition: all 0.3s ease;">
                    ✨ Créer mon crush
                </a>
            @endauth
        </div>

        <div style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 2.5rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div style="font-size: 3rem; margin-bottom: 1rem;">💡</div>
            <h3 style="color: #2c3e50; margin-bottom: 1rem; font-size: 1.3rem;">Le saviez-vous ?</h3>
            <p style="color: #566573; line-height: 1.6;">
                Chaque jour, de nouveaux crushes sont ajoutés par notre communauté. 
                Créez le vôtre et partagez votre histoire avec des milliers de personnes !
            </p>
        </div>
    </div>
@elseif(!$crush)
    <!-- Message quand aucun crush n'existe -->
    <div style="text-align: center; padding: 4rem 2rem; max-width: 700px; margin: 0 auto;">
        <div style="font-size: 8rem; margin-bottom: 2rem;">🎭</div>
        <h1 style="font-size: 2.5rem; color: #2c3e50; margin-bottom: 1.5rem; font-weight: 700;">
            Aucun crush disponible
        </h1>
        <p style="color: #7f8c8d; font-size: 1.2rem; line-height: 1.8; margin-bottom: 3rem;">
            Soyez le premier à partager votre crush avec la communauté !
        </p>
        
        @auth
            <a href="{{ route('account') }}" class="btn btn-success" 
               style="padding: 1.2rem 2.5rem; font-size: 1.2rem; background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); color: white; text-decoration: none; border-radius: 15px; display: inline-block; box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3);">
                ✨ Créer mon crush
            </a>
        @else
            <a href="{{ route('login') }}" class="btn" 
               style="padding: 1.2rem 2.5rem; font-size: 1.2rem; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; text-decoration: none; border-radius: 15px; display: inline-block; box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);">
                🔐 Se connecter pour créer un crush
            </a>
        @endauth
    </div>
@else
<div class="crush-container">
    @if($crush)
        @if(!$hasVoted)
            <div class="crush-card">
                <div class="crush-image-wrapper">
                    @if($crush->image_path)
                        <img src="{{ Storage::url($crush->image_path) }}" alt="Crush" class="crush-image">
                    @endif
                    
                    @if($crush->title)
                        <div class="crush-title">{{ $crush->title }}</div>
                    @endif
                </div>

                <!-- Categories badges -->
                @if($crush->categories && $crush->categories->count() > 0)
                    <div style="padding: 1.5rem 1.5rem 0;">
                        @foreach($crush->categories as $category)
                            <span class="category-badge">{{ $category->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="crush-footer">
                    <div class="crush-text-subtitle"> OP a précisé : </div>
                    <div class="crush-text">
                        {{ $crush->text }}
                    </div>
                </div>
                @auth
                    <form method="POST" action="{{ route('vote.submit') }}" id="voteForm">
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

                    <!-- Skip Button -->
                    <div style="text-align: center; padding: 0 1.5rem 1.5rem; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <a href="{{ route('home', request()->query()) }}" class="skip-btn">
                            ⏭️ Passer ce crush
                        </a>
                    </div>

                <div class="action-buttons">
                    <button onclick="shareThis()" class="btn btn-secondary">
                        <i class="fas fa-share-alt"></i> Partager
                    </button>
                    <button onclick="reportThis()" class="btn btn-danger">
                        <i class="fas fa-flag"></i> Signaler
                    </button>
                </div>
                @else
                    <div class="auth-prompt" style="text-align: center; padding: 1.5rem; background: #f8f9fa;">
                        <p style="margin-bottom: 1rem;">Connectez-vous pour voter sur ce crush</p>
                        <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
                        <span style="margin: 0 0.5rem;">ou</span>
                        <a href="{{ route('register') }}" class="btn">S'inscrire</a>
                    </div>

                                    <div class="action-buttons">
                    <button onclick="shareThis()" class="btn btn-secondary">
                        Partager
                    </button>
                </div>
                @endauth

            </div>
        @else
            <!-- Après le vote, on affiche juste le crush sans les stats (elles sont dans la modale) -->
            <div class="crush-card">
                <!-- Message de vote déjà effectué -->
                <div style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); padding: 1.5rem; margin: 1.5rem; border-radius: 12px; text-align: center; box-shadow: 0 4px 12px rgba(33, 150, 243, 0.2);">
                    <div style="font-size: 2rem; margin-bottom: 0.75rem;">✅</div>
                    <h3 style="color: #1976d2; margin-bottom: 0.5rem; font-size: 1.2rem;">Vous avez déjà voté pour ce crush</h3>
                    @if($userVoteType)
                        <p style="color: #1565c0; font-size: 1rem;">
                            
                            <strong style="font-size: 1.1rem;">
                                @if($userVoteType === 'oui')
                                    Votre vote :  ✓ Oui
                                @elseif($userVoteType === 'non')
                                   Votre vote :   ✗ Non
                                @elseif($userVoteType === 'non_tare')
                                   Votre vote :   ⚠ Non, taré
                                @elseif($userVoteType === 'tare_mais_oui')
                                    Votre vote :   ⚡ Taré mais oui
                                @else
                                    
                                @endif
                            </strong>
                        </p>
                    @endif
                </div>
                
                <div class="crush-image-wrapper">
                    @if($crush->image_path)
                        <img src="{{ Storage::url($crush->image_path) }}" alt="Crush" class="crush-image">
                    @endif
                    
                    @if($crush->title)
                        <div class="crush-title">{{ $crush->title }}</div>
                    @endif
                </div>

                <!-- Categories badges -->
                @if($crush->categories && $crush->categories->count() > 0)
                    <div style="padding: 1.5rem 1.5rem 0;">
                        @foreach($crush->categories as $category)
                            <span class="category-badge">{{ $category->name }}</span>
                        @endforeach
                    </div>
                @endif

                <div class="crush-footer">
                    <div class="crush-text-subtitle"> OP a précisé : </div>
                    <div class="crush-text">
                        {{ $crush->text }}
                    </div>
                </div>

                <div style="text-align: center; padding: 2rem;">
                    <button onclick="showVotedStats()" class="btn" style="padding: 1rem 2rem; font-size: 1.1rem; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);">
                        � Voir les résultats
                    </button>
                </div>
            </div>
        @endif
    @endif
</div>
@endif

<!-- Stats Modal -->
<div id="statsModal" class="stats-modal">
    <div class="stats-modal-content" style="max-width: 800px;">
        <button class="stats-modal-close" onclick="closeStatsModal()">&times;</button>
        
        <!-- Loading animation -->
        <div id="loadingContainer" class="loading-container">
            <div class="loading-spinner"></div>
            <p class="loading-text">Calcul des résultats<span class="loading-dots"></span></p>
        </div>
        
        <!-- Stats content (hidden initially) -->
        <div id="statsContent" class="stats-content">
            <h2 class="stats-modal-title">📊 Résultats du vote</h2>
            
            <div class="stats-grid" id="modalStatsGrid">
                <div class="stat-box" style="background: #e8f5e9;">
                    <div class="stat-number" style="color: #2ecc71;" id="modal-oui">0</div>
                    <div>Oui</div>
                    <small id="modal-oui-percent">0%</small>
                </div>
                <div class="stat-box" style="background: #ffebee;">
                    <div class="stat-number" style="color: #e74c3c;" id="modal-non">0</div>
                    <div>Non</div>
                    <small id="modal-non-percent">0%</small>
                </div>
                <div class="stat-box" style="background: #fff3e0;">
                    <div class="stat-number" style="color: #f39c12;" id="modal-non-tare">0</div>
                    <div>Non, taré</div>
                    <small id="modal-non-tare-percent">0%</small>
                </div>
                <div class="stat-box" style="background: #f3e5f5;">
                    <div class="stat-number" style="color: #9b59b6;" id="modal-tare-mais-oui">0</div>
                    <div>Taré mais oui</div>
                    <small id="modal-tare-mais-oui-percent">0%</small>
                </div>
            </div>
            
            <p style="text-align: center; color: #666; font-size: 1.2rem; margin: 1.5rem 0;">
                Total des votes: <strong id="modal-total">0</strong>
            </p>

            <!-- Comments Section in Modal -->
            @if($crush && $hasVoted)
            <div class="comment-section" style="margin-top: 2rem; border-top: 2px solid #e9ecef; padding-top: 2rem;">
                <h3 style="margin-bottom: 1.5rem; text-align: center;">💬 Commentaires ({{ $crush->comments->count() }})</h3>
                
                @auth
                    @if($userHasCommented)
                        <div style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); padding: 1rem; margin-bottom: 2rem; border-radius: 8px; text-align: center; border-left: 4px solid #f39c12;">
                            <p style="color: #856404; margin: 0; font-weight: 500;">
                                ✓ Vous avez déjà commenté ce crush
                            </p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('comment.store') }}" style="margin-bottom: 2rem;">
                            @csrf
                            <input type="hidden" name="crush_id" value="{{ $crush->id }}">
                            <div class="form-group">
                                <textarea name="text" placeholder="Ajouter un commentaire..." required 
                                          maxlength="{{ $commentMax }}" 
                                          style="resize: vertical; min-height: 80px; width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 8px;"
                                          oninput="document.getElementById('modalCommentCharCount').textContent = this.value.length"></textarea>
                                <small style="color: #6c757d; display: block; margin-top: 0.25rem;">
                                    <span id="modalCommentCharCount">0</span>/{{ $commentMax }} caractères
                                </small>
                            </div>
                            <button type="submit" class="btn btn-success" style="padding: 0.75rem 1.5rem;">💬 Commenter</button>
                        </form>
                    @endif
                @else
                    <p style="text-align: center; color: #666; margin-bottom: 2rem;">
                        <a href="{{ route('login') }}" style="color: #3498db; text-decoration: underline;">Connectez-vous</a> pour commenter
                    </p>
                @endauth

                <div style="max-height: 400px; overflow-y: auto;">
                    @foreach($crush->comments as $comment)
                        <div class="comment" style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <strong style="color: #2c3e50;">{{ $comment->user->name }}</strong>
                                <small style="color: #999;">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <p style="color: #555; line-height: 1.5;">{{ $comment->text }}</p>
                            @auth
                                <div class="comment-actions" style="margin-top: 0.75rem;">
                                    <form method="POST" action="{{ route('comment.react') }}" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                        <input type="hidden" name="type" value="like">
                                        <button type="submit" class="reaction-btn" style="background: none; border: none; padding: 0.5rem 1rem; cursor: pointer; border-radius: 6px; transition: background 0.2s;">
                                            👍 {{ $comment->getLikesCount() }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('comment.react') }}" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                                        <input type="hidden" name="type" value="dislike">
                                        <button type="submit" class="reaction-btn" style="background: none; border: none; padding: 0.5rem 1rem; cursor: pointer; border-radius: 6px; transition: background 0.2s;">
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
            </div>
            @endif
            
            <div style="text-align: center; margin-top: 2rem;">
                <a href="{{ route('home') }}" class="btn" style="padding: 1rem 2.5rem; font-size: 1.1rem; background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; text-decoration: none; border-radius: 12px; display: inline-block; box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);">
                    ➡️ Voir un autre crush
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Vote form handling with AJAX
@if($crush && !$hasVoted && auth()->check())
document.getElementById('voteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitButton = e.submitter;
    
    // Add the vote_type from the clicked button
    if (submitButton && submitButton.name === 'vote_type') {
        formData.set('vote_type', submitButton.value);
    }
    
    // Disable all vote buttons
    const voteButtons = form.querySelectorAll('.vote-btn');
    voteButtons.forEach(btn => {
        btn.disabled = true;
        btn.style.opacity = '0.6';
    });
    
    // Show modal with loading animation
    const modal = document.getElementById('statsModal');
    const loadingContainer = document.getElementById('loadingContainer');
    const statsContent = document.getElementById('statsContent');
    
    loadingContainer.style.display = 'block';
    statsContent.classList.remove('show');
    modal.classList.add('show');
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update modal with stats
            const stats = data.stats;
            const total = data.total;
            
            document.getElementById('modal-oui').textContent = stats.oui;
            document.getElementById('modal-non').textContent = stats.non;
            document.getElementById('modal-non-tare').textContent = stats.non_tare;
            document.getElementById('modal-tare-mais-oui').textContent = stats.tare_mais_oui;
            document.getElementById('modal-total').textContent = total;
            
            // Calculate and display percentages
            if (total > 0) {
                document.getElementById('modal-oui-percent').textContent = Math.round(stats.oui / total * 100) + '%';
                document.getElementById('modal-non-percent').textContent = Math.round(stats.non / total * 100) + '%';
                document.getElementById('modal-non-tare-percent').textContent = Math.round(stats.non_tare / total * 100) + '%';
                document.getElementById('modal-tare-mais-oui-percent').textContent = Math.round(stats.tare_mais_oui / total * 100) + '%';
            }
            
            // Hide loading and show stats with a smooth transition
            setTimeout(() => {
                loadingContainer.classList.add('fade-out');
                setTimeout(() => {
                    loadingContainer.style.display = 'none';
                    loadingContainer.classList.remove('fade-out');
                    statsContent.classList.add('show');
                }, 300); // Wait for fade-out animation
            }, 600); // Small delay for better UX
        } else {
            modal.classList.remove('show');
            alert('Une erreur est survenue lors du vote.');
            voteButtons.forEach(btn => {
                btn.disabled = false;
                btn.style.opacity = '1';
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        modal.classList.remove('show');
        alert('Une erreur est survenue lors du vote.');
        voteButtons.forEach(btn => {
            btn.disabled = false;
            btn.style.opacity = '1';
        });
    });
});
@endif

function showVotedStats() {
    // Show modal with pre-loaded stats
    const modal = document.getElementById('statsModal');
    const loadingContainer = document.getElementById('loadingContainer');
    const statsContent = document.getElementById('statsContent');
    
    // Hide loading, show stats directly (they're already in the page)
    loadingContainer.style.display = 'none';
    statsContent.style.display = 'block';
    
    @if($crush && $hasVoted)
        @php
            $stats = $crush->getVoteStats();
            $total = array_sum($stats);
        @endphp
        
        // Update modal with PHP stats
        document.getElementById('modal-oui').textContent = {{ $stats['oui'] }};
        document.getElementById('modal-non').textContent = {{ $stats['non'] }};
        document.getElementById('modal-non-tare').textContent = {{ $stats['non_tare'] }};
        document.getElementById('modal-tare-mais-oui').textContent = {{ $stats['tare_mais_oui'] }};
        document.getElementById('modal-total').textContent = {{ $total }};
        
        @if($total > 0)
            document.getElementById('modal-oui-percent').textContent = '{{ round($stats['oui'] / $total * 100) }}%';
            document.getElementById('modal-non-percent').textContent = '{{ round($stats['non'] / $total * 100) }}%';
            document.getElementById('modal-non-tare-percent').textContent = '{{ round($stats['non_tare'] / $total * 100) }}%';
            document.getElementById('modal-tare-mais-oui-percent').textContent = '{{ round($stats['tare_mais_oui'] / $total * 100) }}%';
        @endif
    @endif
    
    modal.classList.add('show');
}

function closeStatsModal() {
    const modal = document.getElementById('statsModal');
    const loadingContainer = document.getElementById('loadingContainer');
    const statsContent = document.getElementById('statsContent');
    
    modal.classList.remove('show');
    
    // Reset modal state after closing animation
    setTimeout(() => {
        loadingContainer.style.display = 'block';
        loadingContainer.classList.remove('fade-out');
        statsContent.classList.remove('show');
    }, 300);
}

// Close modal when clicking outside
document.getElementById('statsModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatsModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeStatsModal();
    }
});

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
