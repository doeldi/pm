<!-- resources/views/report/index.blade.php -->
@extends('layouts.layout')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabel Daftar Laporan -->
    <div class="card shadow rounded-3 border-0 mb-4">
        <div class="card-header bg-primary bg-gradient text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <span class="h5 fw-bold mb-0"><i class="fas fa-list-alt me-2"></i>Daftar Pengaduan</span>
                <a href="{{ route('responses.export') }}" class="btn btn-light btn-sm d-flex align-items-center hover-shadow">
                    <i class="fas fa-file-excel me-2"></i>Export (.xlsx)
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="px-4 py-3 text-center">Gambar & Pengirim</th>
                            <th class="px-4 py-3">Lokasi & Tanggal</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3 text-center">Jumlah Vote</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <a href="{{ asset('storage/' . $report->image) }}" data-bs-toggle="modal" data-bs-target="#imageModal{{ $report->id }}" class="hover-zoom">
                                            <img src="{{ asset('storage/' . $report->image) }}"
                                                class="rounded-circle border shadow-sm" width="60" height="60" style="object-fit: cover;">
                                        </a>
                                        <div class="ms-3">
                                            <span class="fw-bold text-dark d-block">{{ $report->user->email }}</span>
                                            <small class="text-muted"><i class="fas fa-user me-1"></i>Pengirim</small>
                                        </div>
                                    </div>

                                    <!-- Modal Preview Gambar -->
                                    <div class="modal fade" id="imageModal{{ $report->id }}" tabindex="-1" aria-labelledby="imageModalLabel{{ $report->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered">
                                            <div class="modal-content border-0">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title" id="imageModalLabel{{ $report->id }}"><i class="fas fa-image me-2"></i>Preview Gambar</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center p-0">
                                                    <img src="{{ asset('storage/' . $report->image) }}" alt="Preview" class="img-fluid">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-primary mb-1"><i class="fas fa-map-marker-alt me-2"></i>{{ $report->village }}</div>
                                    <div class="small text-muted mb-1">{{ $report->subdistrict }}, {{ $report->regency }},
                                        {{ $report->province }}</div>
                                    <small class="text-muted d-flex align-items-center">
                                        <i class="far fa-calendar-alt me-2"></i>
                                        {{ $report->created_at->format('d F Y') }}
                                    </small>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="mb-0 text-dark">{{ Str::limit($report->description, 50) }}</p>
                                    @if(strlen($report->description) > 50)
                                        <small class="text-primary cursor-pointer">Baca selengkapnya...</small>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="badge bg-info bg-gradient rounded-pill px-3 py-2">
                                        <i class="fas fa-thumbs-up me-1"></i>{{ $report->voting }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle d-flex align-items-center mx-auto" type="button"
                                            id="actionMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-cog me-2"></i>Aksi
                                        </button>
                                        <ul class="dropdown-menu shadow border-0" aria-labelledby="actionMenu">
                                            <li>
                                                @if($report->responses && $report->responses->first())
                                                    <a href="{{ route('responses.show', $report->id) }}" class="dropdown-item text-success d-flex align-items-center">
                                                        <i class="fas fa-check-circle me-2"></i> Sudah ditindaklanjuti ({{ $report->responses->first()->response_status }})
                                                    </a>
                                                @else
                                                    <a class="dropdown-item d-flex align-items-center" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#responseModal{{ $report->id }}">
                                                        <i class="fas fa-info-circle me-2"></i>Tindak lanjut
                                                    </a>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Modal Tindak Lanjut -->
                                    <div class="modal fade" id="responseModal{{ $report->id }}" tabindex="-1"
                                        aria-labelledby="responseModalLabel{{ $report->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title" id="responseModalLabel{{ $report->id }}">
                                                        <i class="fas fa-reply me-2"></i>Tindak Lanjut Pengaduan
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('responses.store', $report->id) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-4">
                                                            <label class="form-label fw-bold">Status Tanggapan</label>
                                                            <select class="form-select" name="response_status" required>
                                                                <option value="REJECT">REJECT</option>
                                                                <option value="ON_PROCESS">ON PROCESS</option>
                                                            </select>
                                                        </div>
                                                        <div class="text-end">
                                                            <button type="button" class="btn btn-light me-2"
                                                                data-bs-dismiss="modal">
                                                                <i class="fas fa-times me-2"></i>Tutup
                                                            </button>
                                                            <button type="submit" class="btn btn-primary px-4">
                                                                <i class="fas fa-save me-2"></i>Simpan
                                                            </button>
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
