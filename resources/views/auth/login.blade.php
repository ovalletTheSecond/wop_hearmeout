@extends('layouts.app')

@section('title', 'Connexion - Hear Me Out')

@section('content')
<div style="max-width: 400px; margin: 0 auto;">
    <h2 style="margin-bottom: 1.5rem; text-align: center;">Connexion</h2>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="list-style: none;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}" style="margin-bottom: 1.5rem;">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn" style="width: 100%;">Se connecter</button>
    </form>

    <div style="text-align: center; margin: 1.5rem 0;">
        <p style="color: #666; margin-bottom: 1rem;">ou</p>
        <a href="{{ route('login.google') }}" class="btn btn-google" style="width: 100%; display: block; text-align: center;">
            Se connecter avec Google
        </a>
    </div>
</div>
@endsection
