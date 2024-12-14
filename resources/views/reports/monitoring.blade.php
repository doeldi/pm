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
                    <div class="collapse show" id="reportContent-{{ $report->id }}">
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
                                                @if ($report->responses->first()->response_status == 'DONE')
                                                    <div class="timeline-item">
                                                        <div class="timeline-point bg-primary"></div>
                                                        <div class="timeline-content">
                                                            <div class="timeline-time">
                                                                <i class="fas fa-check me-1"></i>
                                                                {{ $report->responses->first()->updated_at->format('d/m/Y H:i') }}
                                                            </div>
                                                            <div class="timeline-body">
                                                                Pengaduan selesai
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
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
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-2"></i>Belum ada tanggapan
                                                yang ditambahkan
                                            </div>
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
