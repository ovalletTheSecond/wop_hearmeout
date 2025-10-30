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
            <div class="password-requirements">
                <div class="requirement" id="req-length">
                    <i class="fas fa-check"></i> 8 caractères minimum
                </div>
                <div class="requirement" id="req-number">
                    <i class="fas fa-check"></i> Au moins un chiffre
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <style>
            .password-requirements {
                margin-top: 0.5rem;
                font-size: 0.9rem;
            }
            .requirement {
                color: #666;
                margin: 0.2rem 0;
            }
            .requirement i {
                color: #ccc;
                margin-right: 0.5rem;
            }
            .requirement.valid {
                color: #2ecc71;
            }
            .requirement.valid i {
                color: #2ecc71;
            }
        </style>

        <script>
            document.getElementById('password').addEventListener('input', function(e) {
                const password = e.target.value;
                
                // Validation des critères simplifiés
                const requirements = {
                    'req-length': password.length >= 8,
                    'req-number': /[0-9]/.test(password)
                };

                // Mise à jour visuelle
                Object.entries(requirements).forEach(([id, valid]) => {
                    const element = document.getElementById(id);
                    if (valid) {
                        element.classList.add('valid');
                    } else {
                        element.classList.remove('valid');
                    }
                });
            });
        </script>

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