@extends('layouts.app')

@section('title', 'Dashboard - Traccar TF')

@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="/dashboard">Traccar TF</a>
        <div class="d-flex align-items-center">
            <span class="text-light me-3">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm">Déconnexion</button>
            </form>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Bienvenue, {{ Auth::user()->name }} !</h1>
            <p class="text-muted">Vous êtes connecté avec succès.</p>
        </div>
    </div>
</div>
@endsection
