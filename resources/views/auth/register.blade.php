@extends('layouts.app')

@section('title', 'Inscription - Hear Me Out')

@section('content')
<div style="max-width: 400px; margin: 0 auto;">
    <h2 style="margin-bottom: 1.5rem; text-align: center;">Inscription</h2>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="list-style: none;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}" style="margin-bottom: 1.5rem;">
        @csrf
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn" style="width: 100%;">S'inscrire</button>
    </form>

    <div style="text-align: center; margin: 1.5rem 0;">
        <p style="color: #666; margin-bottom: 1rem;">ou</p>
        <a href="{{ route('login.google') }}" class="btn btn-google" style="width: 100%; display: block; text-align: center;">
            S'inscrire avec Google
        </a>
    </div>

    <div style="text-align: center; margin-top: 1rem;">
        <p>Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
    </div>
</div>
@endsection