@extends('layouts.app')

@section('title', 'Connexion - Traccar TF')

@section('body-class', 'd-flex align-items-center justify-content-center py-5')

@push('styles')
<style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        }
        .login-card {
            max-width: 400px;
            width: 100%;
        }
</style>
@endpush

@section('content')
<div class="login-card">
    <div class="card shadow-lg border-0">
        <div class="card-body p-5">
            <h2 class="text-center mb-4 fw-bold">Connexion</h2>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Adresse Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">Se connecter</button>
            </form>

            <!-- <div class="text-center mt-4">
                <p class="text-muted">Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a></p>
            </div> -->
        </div>
    </div>
</div>
@endsection
