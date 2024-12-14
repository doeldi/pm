@extends('layouts.layout')

@section('content')
    <div class="content-section">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if (Session::get('failed'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ Session::get('failed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif            

        <div class="text-center mb-4">
            <h4>Selamat Datang di Sistem Pengaduan Masyarakat</h4>
            <p>Sampaikan laporan Anda langsung kepada instansi pemerintah berwenang</p>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="section-container mb-3">
                    <div class="text-center">
                        <i class="fas fa-edit fa-3x mb-3 text-primary"></i>
                        <h5 class="feature-title">Buat Pengaduan</h5>
                        <p class="feature-text">Buat pengaduan baru terkait masalah di lingkungan Anda
                        </p>
                        <a href="{{ route('report.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus-circle me-2"></i>Buat Pengaduan
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="section-container mb-3">
                    <div class="text-center">
                        <i class="fas fa-history fa-3x mb-3 text-secondary"></i>
                        <h5 class="feature-title">Riwayat Pengaduan</h5>
                        <p class="feature-text">Lihat status dan riwayat pengaduan yang telah Anda buat
                        </p>
                        <a href="{{ route('report.data-report') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-clock me-2"></i>Lihat Riwayat
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="section-container mb-3">
                    <div class="text-center">
                        <i class="fas fa-user fa-3x mb-3 text-success"></i>
                        <h5 class="feature-title">Pengaduan Saya</h5>
                        <p class="feature-text">Lihat pengaduan yang telah Anda buat
                        </p>
                        <a href="{{ route('report.myReports') }}" class="btn btn-success w-100">
                            <i class="fas fa-user-circle me-2"></i>Pengaduan Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
