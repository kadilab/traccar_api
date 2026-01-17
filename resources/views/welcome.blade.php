@extends('layouts.app')

@section('title', 'Welcome to Laravel API')

@section('body-class', 'd-flex align-items-center justify-content-center')

@push('styles')
<style>
    body {
        min-height: 100vh;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }
</style>
@endpush

@section('content')
<div class="text-center">
    <h1 class="display-4 fw-bold text-white mb-4">Laravel API is Running!</h1>
    <p class="text-secondary fs-5 mb-5">Votre API Traccar est prête à être utilisée</p>
    <div class="d-flex gap-3 justify-content-center">
        <a href="/api/traccar/health" class="btn btn-primary btn-lg">
            Vérifier Health
        </a>
        <a href="/api/traccar/devices" class="btn btn-success btn-lg">
            Voir Devices
        </a>
    </div>
</div>
@endsection