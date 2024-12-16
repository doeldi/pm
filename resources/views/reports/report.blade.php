@extends('layouts.layout')

@section('content')
    <div class="row g-4">
        @if (Session::get('failed'))
            <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
                {{ Session::get('failed') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <!-- Bagian Kiri - Informasi -->
        <div class="col-lg-4">
            <div class="info-section shadow bg-light">
                <h4 class="mb-4">Informasi Pembuatan Pengaduan</h4>
                <ul class="list-unstyled">
                    <li class="mb-3"><i class="fas fa-check-circle me-2 text-primary"></i>Pilih provinsi tempat kejadian
                    </li>
                    <li class="mb-3"><i class="fas fa-check-circle me-2 text-primary"></i>Isi detail pengaduan dengan
                        jelas
                    </li>
                    <li class="mb-3"><i class="fas fa-check-circle me-2 text-primary"></i>Sertakan bukti pendukung jika
                        ada
                    </li>
                    <li class="mb-3"><i class="fas fa-check-circle me-2 text-primary"></i>Pastikan data yang diisi valid
                    </li>
                    <li class="mb-3"><i class="fas fa-check-circle me-2 text-primary"></i>Pengaduan akan diproses dalam 24
                        jam</li>
                    <li class="mt-4">
                        <a href="{{ route('report.create') }}" class="btn btn-primary w-100 py-3">
                            <i class="fas fa-plus-circle me-2"></i>Buat Pengaduan
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bagian Kanan - Daftar Pengaduan -->
        <div class="col-lg-8">
            <div id="complaintsList" class="bg-white p-4 rounded-4 shadow">
                <form class="d-flex mb-3" role="search" action="{{ route('report.data-report') }}" method="GET">
                    <select id="provinceDropdown" class="form-select" name="PROVINCE">
                        <option value="">Semua Provinsi</option>
                    </select>
                    <button class="btn btn-outline-primary ms-2" type="submit">Search</button>
                </form>

                <div class="mt-4" id="reportContainer">
                    @if ($reports->isEmpty())
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            Belum ada data pengaduan yang tersedia.
                        </div>
                    @else
                        @foreach ($reports as $report)
                            <div class="card mb-4 report-card hover-shadow" id="report-{{ $report->id }}"
                                data-province-name="{{ $report->province }}">
                                <div class="card-body p-4">
                                    <!-- Informasi Laporan -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">
                                            <i class="far fa-calendar-alt me-2 text-primary"></i>
                                            {{ $report->created_at->format('d F Y') }}
                                            <small class="text-muted"> | {{ $report->created_at->diffForHumans() }}</small>
                                        </h5>
                                        <a href="{{ route('report.show', $report->id) }}" class="btn btn-primary">
                                            <i class="fas fa-eye me-1"></i> Lihat Artikel
                                        </a>
                                    </div>

                                    <div class="d-flex align-items-center mb-3">
                                        <i class="fas fa-user-circle me-2 text-primary"></i>
                                        <span>{{ $report->user->email }}</span>
                                    </div>
                                    <div class="badge bg-primary mb-3 px-3 py-2">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $report->type }} | {{ $report->province }}
                                    </div>

                                    @if ($report->image)
                                        <div class="mt-3 mb-3">
                                            <img src="{{ asset('storage/' . $report->image) }}"
                                                class="img-fluid rounded shadow" alt="Report Image">
                                        </div>
                                    @endif

                                    <p class="card-text">{{ $report->description }}</p>

                                    <!-- Detail Voting -->
                                    <div class="stats-container mt-4">
                                        <div class="d-flex gap-4">
                                            <div class="stat-item">
                                                <i class="fas fa-eye me-2"></i>
                                                <span class="fw-bold">{{ $report->viewers }}</span>
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-thumbs-up me-2"></i>
                                                <span class="fw-bold">{{ $report->voting }}</span>
                                            </div>
                                            <div class="stat-item">
                                                <i class="fas fa-comment me-2"></i>
                                                <span class="fw-bold">{{ $report->comments->count() }}</span>
                                            </div>
                                        </div>

                                        <!-- Form Vote dan Unvote -->
                                        <div class="d-flex gap-2">
                                            {{-- <form action="{{ route('report.vote', $report->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary">
                                                    <i class="fas fa-thumbs-up me-2"></i>Vote
                                                </button>
                                            </form>
                                            <form action="{{ route('report.unvote', $report->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-thumbs-down me-2"></i>Unvote
                                                </button>
                                            </form> --}}
                                            <form action="{{ route('report.toggleVote', $report->id) }}" method="POST">
                                                @csrf
                                                @if (in_array($report->id, session('voted_reports', [])))
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-thumbs-down me-2"></i>Unvote
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-thumbs-up me-2"></i>Vote
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-bottom mb-4"></div>
                            </div>
                        @endforeach

                        {{-- <div class="d-flex justify-content-center mt-4">
                            {{ $reports->links('pagination::simple-bootstrap-5') }}
                        </div> --}}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        body {
            background-color: #f0f2f5;
            color: #1a1a1a;
        }

        .container {
            max-width: 1200px;
        }

        .info-section {
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 1rem;
            border-left: 5px solid #0d6efd;
        }

        .info-section h4 {
            color: #0d6efd;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .form-select {
            padding: 0.8rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            font-size: 1rem;
        }

        .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            border-color: #0d6efd;
        }

        .card {
            border: none;
            border-radius: 1rem;
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15) !important;
        }

        .card-title {
            color: #0d6efd;
            font-weight: 600;
        }

        .alert-info {
            background-color: #cfe2ff;
            color: #084298;
            border: none;
            border-radius: 0.5rem;
        }

        .badge {
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-primary {
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, 0.15);
        }

        .btn-outline-primary {
            border: 2px solid #0d6efd;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-danger {
            border: 2px solid #dc3545;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
            transform: translateY(-2px);
        }

        .stats-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
        }

        .stat-item {
            color: #0d6efd;
            font-size: 1rem;
        }

        .shadow {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
        }

        img.img-fluid {
            border-radius: 0.5rem;
            max-height: 300px;
            object-fit: cover;
            width: 100%;
        }

        /* .pagination {
                                margin-bottom: 0;
                                gap: 0.5rem;
                            }

                            .page-link {
                                color: #0d6efd;
                                border: 1px solid #dee2e6;
                                padding: 0.75rem 1rem;
                                border-radius: 0.5rem;
                                margin: 0;
                                font-weight: 500;
                                transition: all 0.3s ease;
                            }

                            .page-link:hover {
                                background-color: #0d6efd;
                                color: white;
                                border-color: #0d6efd;
                                transform: translateY(-2px);
                            }

                            .page-item.active .page-link {
                                background-color: #0d6efd;
                                border-color: #0d6efd;
                                color: white;
                                box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, 0.15);
                            }

                            .page-item.disabled .page-link {
                                color: #6c757d;
                                pointer-events: none;
                                background-color: #f8f9fa;
                                border-color: #dee2e6;
                            }

                            .pagination .page-item:first-child .page-link,
                            .pagination .page-item:last-child .page-link {
                                border-radius: 0.5rem;
                            } */
    </style>
@endpush

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            loadProvinces();

            function loadProvinces() {
                $.ajax({
                    url: 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
                    method: 'GET',
                    success: function(data) {
                        let dropdown = $('#provinceDropdown');
                        $.each(data, function(key, value) {
                            dropdown.append($('<option></option>')
                                .attr('value', value.name)
                                .text(value.name));
                        });
                    },
                    error: function(error) {
                        console.error('Error fetching provinces:', error);
                    }
                });
            }
        });
    </script>
@endpush
