@extends('layouts.layout')

@section('content')
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title fw-bold text-dark mb-3">Deskripsi Pengaduan</h5>
            <p class="card-text lead">{{ $response->report->description }}</p>

            <div class="mt-4">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <h6 class="mb-0">Status: <span class="badge bg-primary">{{ $response->response_status }}</span></h6>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-event me-2"></i>
                    <h6 class="mb-0">Tanggal Dibuat: {{ $response->created_at->format('d/m/Y H:i') }}</h6>
                </div>
            </div>

            @if ($response->response_status != 'DONE')
                <div class="mt-4 d-flex justify-content-end">
                    <form action="{{ route('responses.update', $response->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="response_status" value="DONE">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Nyatakan Selesai
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title fw-bold text-dark mb-4">Progress History</h5>
            @if ($response->progress->count() > 0)
                <div class="timeline position-relative">
                    <div class="timeline-line position-absolute h-100"
                        style="left: 9px; width: 2px; background-color: #0d6efd;"></div>
                    @foreach ($response->progress as $progress)
                        <div class="timeline-item ps-5 mb-4 position-relative">
                            <div class="timeline-point position-absolute" style="left: 0; top: 0;">
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 20px; height: 20px;">
                                    <div class="bg-white rounded-circle" style="width: 8px; height: 8px;"></div>
                                </div>
                            </div>
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <p class="mb-2 text-dark">{{ $progress->histories }}</p>
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $progress->created_at->format('d/m/Y H:i') }}
                                    </small>
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

    <h2 class="mb-4 text-primary fw-bold">Tambah Progress</h2>
    <div class="card shadow-sm">
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
@endsection

