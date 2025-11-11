@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="bg-white rounded-4 shadow p-4 p-md-5">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h1 class="h4 m-0 d-flex align-items-center">
                        <i class="fas fa-tachometer-alt text-success me-2"></i>
                        Tableau de bord
                    </h1>
                    <span class="badge bg-success bg-gradient">Connecté</span>
                </div>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <p class="text-muted mb-4">Bienvenue, vous êtes connecté.</p>

                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="stat-card rounded-4 p-3 h-100">
                            <div class="d-flex align-items-center">
                                <div class="rounded-3 bg-green-light p-2 me-3">
                                    <i class="fas fa-map-marker-alt text-green"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Pharmacies totales</div>
                                    <div class="fw-bold">—</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card rounded-4 p-3 h-100">
                            <div class="d-flex align-items-center">
                                <div class="rounded-3 bg-green-light p-2 me-3">
                                    <i class="fas fa-check-circle text-green"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Pharmacies proches</div>
                                    <div class="fw-bold">—</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card rounded-4 p-3 h-100">
                            <div class="d-flex align-items-center">
                                <div class="rounded-3 bg-green-light p-2 me-3">
                                    <i class="fas fa-user-md text-green"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">Votre statut</div>
                                    <div class="fw-bold">Compte actif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
