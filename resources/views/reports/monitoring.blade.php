@extends('layouts.layout')

@section('content')
    <div class="mt-4" id="reportContainer">
        @if ($reports->isEmpty())
            <div class="alert alert-info text-center">
                Anda belum memiliki pengaduan. 
            </div>
        @else
            @foreach ($reports as $report)
                <div class="card mb-4 report-card">
                    <div class="card-header d-flex justify-content-between align-items-center" role="button"
                        data-bs-toggle="collapse" data-bs-target="#reportContent-{{ $report->id }}" aria-expanded="true">
                        <h5 class="mb-0">Pengaduan - {{ $report->created_at->format('d F Y') }}</h5>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="collapse hide" id="reportContent-{{ $report->id }}">
                        <div class="card-body">
                            <ul class="nav nav-pills mb-3" id="reportTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#data-{{ $report->id }}" type="button">
                                        <i class="fas fa-file-alt me-2"></i>Data Pengaduan
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#image-{{ $report->id }}" type="button">
                                        <i class="fas fa-image me-2"></i>Gambar
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#status-{{ $report->id }}" type="button">
                                        <i class="fas fa-info-circle me-2"></i>Detail Status
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="data-{{ $report->id }}">
                                    <div class="report-info p-3 bg-light rounded">
                                        <div class="mb-3">
                                            <h6 class="text-primary"><i class="fas fa-tag me-2"></i>Jenis Pengaduan</h6>
                                            <p class="ms-4">{{ $report->type }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-primary"><i class="fas fa-align-left me-2"></i>Deskripsi</h6>
                                            <p class="ms-4">{{ $report->description }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <h6 class="text-primary"><i class="fas fa-map-marker-alt me-2"></i>Lokasi</h6>
                                            <p class="ms-4">{{ $report->village }}, {{ $report->subdistrict }},
                                                {{ $report->regency }}, {{ $report->province }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="image-{{ $report->id }}">
                                    @if ($report->image)
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $report->image) }}"
                                                class="img-fluid rounded shadow" alt="Report Image">
                                        </div>
                                    @else
                                        <div class="text-center text-muted py-5">
                                            <i class="fas fa-image fa-3x mb-3"></i>
                                            <p>Tidak ada gambar</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="tab-pane fade" id="status-{{ $report->id }}">
                                    <div class="d-flex flex-column align-items-center p-3">
                                        <div class="status-timeline mb-4">
                                            <div class="status-step {{ $report->responses->isEmpty() ? 'pending' : '' }}">
                                                <i class="fas fa-clock"></i>
                                                <span>Pending</span>
                                            </div>
                                            <div
                                                class="status-step {{ !$report->responses->isEmpty() && $report->responses->first()->response_status == 'ON_PROCESS' ? 'process' : '' }}">
                                                <i class="fas fa-cog"></i>
                                                <span>Diproses</span>
                                            </div>
                                            <div
                                                class="status-step {{ !$report->responses->isEmpty() && $report->responses->first()->response_status == 'DONE' ? 'done' : '' }}">
                                                <i class="fas fa-check"></i>
                                                <span>Selesai</span>
                                            </div>
                                        </div>
                                        @if ($report->responses->first() && $report->responses->first()->progress->count() > 0)
                                            @if ($report->responses->first() && $report->responses->first()->response_status == 'DONE')
                                                <div class="alert alert-info"><i class="fas fa-check"></i>
                                                    Pengaduan ini telah selesai.
                                                </div>
                                            @else
                                                <div class="timeline position-relative w-100">
                                                    <div class="timeline-line"></div>
                                                    @foreach ($report->responses->first()->progress as $progress)
                                                        <div class="timeline-item">
                                                            <div class="timeline-point"></div>
                                                            <div class="timeline-content">
                                                                <div class="timeline-time">
                                                                    <i class="fas fa-clock me-1"></i>
                                                                    {{ $progress->created_at->format('d/m/Y H:i') }}
                                                                </div>
                                                                <div class="timeline-body">
                                                                    {{ $progress->histories }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @elseif (now()->diffInDays($report->created_at) >= 1 && $report->responses->isEmpty())
                                            <div class="mb-3">
                                                <small class="text-danger">Staff tidak merespon pengaduan ini selama 24
                                                    jam.</small>
                                                <button type="button" class="delete-btn" data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $report->id }}">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal{{ $report->id }}" tabindex="-1"
                                                    aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi
                                                                    Hapus</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Apakah Anda yakin ingin menghapus pengaduan ini?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Batal</button>
                                                                <form action="{{ route('report.destroy', $report->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger">Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @if ($report->responses->isEmpty())
                                                <div class="alert alert-danger">
                                                    <i class="fas fa-info-circle"></i> Belum ada tanggapan
                                                    yang ditambahkan
                                                </div>
                                            @else
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-info-circle"></i> Menunggu progress
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@push('styles')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #fff;
            border-bottom: none;
            cursor: pointer;
            padding: 1rem 1.25rem;
        }

        .card-header:hover {
            background-color: #f8f9fa;
        }

        .nav-pills .nav-link {
            color: #6c757d;
            border-radius: 20px;
            padding: 8px 16px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link:hover {
            background-color: #e9ecef;
            color: #0d6efd;
        }

        .nav-pills .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }

        .report-info {
            border-left: 4px solid #0d6efd;
        }

        .delete-btn {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 20px;
        }

        .delete-btn:hover {
            background-color: #dc3545;
            color: white;
        }

        .status-timeline {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 600px;
            position: relative;
            margin: 20px 0;
        }

        .status-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .status-step i {
            background-color: #fff;
            border: 2px solid #dee2e6;
            border-radius: 50%;
            padding: 10px;
            margin-bottom: 5px;
            color: #6c757d;
        }

        .status-step.pending i {
            border-color: #dc3545;
            color: #dc3545;
        }

        .status-step.process i {
            border-color: #eea404;
            color: #eea404;
        }

        .status-step.done i {
            border-color: #0d6efd;
            color: #0d6efd;
        }

        .status-timeline::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #dee2e6;
            z-index: 0;
        }

        .img-fluid {
            transition: transform 0.3s ease;
        }

        .img-fluid:hover {
            transform: scale(1.05);
        }

        /* Timeline styles */
        .timeline {
            padding: 20px 0;
            max-width: 800px;
            margin: 0 auto;
        }

        .timeline-line {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 100%;
            background: linear-gradient(to bottom, #0d6efd 0%, #83b8ff 100%);
            top: 0;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .timeline-point {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 20px;
            background: #fff;
            border: 3px solid #0d6efd;
            border-radius: 50%;
            z-index: 2;
        }

        .timeline-content {
            width: 45%;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
            margin-left: 55%;
        }

        .timeline-item:nth-child(even) .timeline-content {
            margin-left: 0;
            margin-right: 55%;
        }

        .timeline-time {
            color: #6c757d;
            font-size: 0.9em;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .timeline-body {
            color: #343a40;
            line-height: 1.6;
        }

        .timeline-content::before {
            content: '';
            position: absolute;
            top: 10px;
            left: -10px;
            width: 20px;
            height: 20px;
            background: white;
            transform: rotate(45deg);
            box-shadow: -2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timeline-item:nth-child(even) .timeline-content::before {
            left: auto;
            right: -10px;
            box-shadow: 2px -2px 4px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .timeline-content {
                width: 85%;
                margin-left: 15%;
            }

            .timeline-line {
                left: 40px;
            }

            .timeline-point {
                left: 40px;
            }

            .timeline-item:nth-child(even) .timeline-content {
                margin-left: 15%;
                margin-right: 0;
            }

            .timeline-content::before {
                display: none;
            }
        }
    </style>
@endpush
