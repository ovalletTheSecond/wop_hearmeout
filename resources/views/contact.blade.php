@extends('layouts.app')

@section('title', 'Me Contacter - Hear Me Out')

@section('content')
@php
    $messageMax = config('content.message.max', 2000);
@endphp
<div style="max-width: 600px; margin: 0 auto; padding: 2rem;">
    <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem;">
        <img src="{{ asset('resources/views/img_website/profil_jordy.png') }}" alt="Jordy" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
        <div>
            <h2>Me faire parvenir une missive</h2>
            <p style="color: #666;">Partagez vos suggestions pour améliorer le concept de Hear Me Out</p>
        </div>
    </div>

    <!-- Info box pour suggestions catégories -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-radius: 8px; margin-bottom: 2rem; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);">
        <h3 style="margin-bottom: 0.75rem; font-size: 1.25rem;">💡 Proposez une nouvelle catégorie !</h3>
        <p style="margin: 0; opacity: 0.95; line-height: 1.6;">
            Vous avez une idée de catégorie qui n'existe pas encore ? N'hésitez pas à me la suggérer dans votre message ! 
            Les meilleures propositions seront ajoutées au site pour enrichir l'expérience de tous.
        </p>
    </div>

    @auth
        <div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <form action="{{ route('messages.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="content">Votre message <small style="color: #6c757d;">(max {{ $messageMax }} caractères)</small></label>
                    <textarea id="content" name="content" rows="5" required 
                        maxlength="{{ $messageMax }}"
                        oninput="document.getElementById('messageCharCount').textContent = this.value.length"
                        placeholder="Partagez vos idées, suggestions ou remarques pour améliorer le site..."></textarea>
                    <small style="color: #6c757d; display: block; margin-top: 0.25rem;">
                        <span id="messageCharCount">0</span>/{{ $messageMax }} caractères
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    @else
        <div style="text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 8px;">
            <p>Connectez-vous pour m'envoyer un message</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
        </div>
    @endauth
</div>
@endsection