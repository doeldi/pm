@extends('layouts.layout')

@section('content')
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title fw-bold text-primary mb-4">
                <i class="fas fa-user me-2"></i>
                {{ $response->report->user->email }}
            </h5>

            <div class="d-flex align-items-center gap-4 mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-event me-2 text-muted"></i>
                    <h6 class="mb-0 text-muted">{{ $response->created_at->format('d/m/Y H:i') }}</h6>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <h6 class="mb-0">Status:
                        <span class="badge bg-{{ $response->response_status == 'DONE' ? 'success' : 'primary' }} ms-1">
                            {{ $response->response_status }}
                        </span>
                    </h6>
                </div>
            </div>

            <div class="p-4 border rounded-3 bg-light shadow-sm">
                <p class="card-text lead mb-0">{{ $response->report->description }}</p>
            </div>

            @if ($response->response_status != 'DONE')
                <div class="mt-4 d-flex justify-content-end">
                    <form action="{{ route('responses.update', $response->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="response_status" value="DONE">
                        <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                            <i class="bi bi-check-circle me-2"></i>
                            Nyatakan Selesai
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title fw-bold text-primary mb-4">Progress History</h5>
            @if ($response->progress->count() > 0)
                <div class="timeline position-relative">
                    <div class="timeline-line position-absolute h-100 bg-primary" style="left: 9px; width: 2px;"></div>
                    @foreach ($response->progress as $progress)
                        <div class="timeline-item ps-5 mb-4 position-relative">
                            <div class="timeline-point position-absolute bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="left: 0; top: 0; width: 20px; height: 20px;">
                                <div class="bg-white rounded-circle" style="width: 8px; height: 8px;"></div>
                            </div>
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <p class="mb-2 text-dark">{{ $progress->histories }}</p>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted d-flex align-items-center mb-2">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $progress->created_at->format('d/m/Y H:i') }}
                                        </small>
                                        @if ($response->response_status != 'DONE')
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $progress->id }}">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        @endif
                                    </div>
                                    <div class="modal fade" id="deleteModal{{ $progress->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus progress ini?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('responses.destroy', $progress->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>Belum ada progress yang ditambahkan
                </div>
            @endif
        </div>
    </div>

    @if ($response->response_status != 'DONE')
        <h2 class="mb-4 text-primary fw-bold">Tambah Progress</h2>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ route('responses.storeProgress', $response->id) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <textarea name="histories" id="histories" class="form-control @error('histories') is-invalid @enderror" rows="4"
                            placeholder="Deskripsikan progress..." required style="resize: none;">{{ old('histories') }}</textarea>

                        @error('histories')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan Progress
                        </button>
                        <a href="{{ route('responses.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
