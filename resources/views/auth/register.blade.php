@extends('layouts.app')

@section('title', 'Inscription - Traccar TF')

@section('body-class', 'd-flex align-items-center justify-content-center py-5')

@push('styles')
<style>
    body {
        min-height: 100vh;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }
    .register-card {
        max-width: 450px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="register-card">
    <div class="card shadow-lg border-0">
        <div class="card-body p-5">
            <h2 class="text-center mb-4 fw-bold">Inscription</h2>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nom complet</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="form-text">Minimum 8 caractères</div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">S'inscrire</button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted">Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
