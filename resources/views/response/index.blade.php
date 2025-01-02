@extends('layouts.layout')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (Session::get('failed'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            {{ Session::get('failed') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            {{ Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabel Daftar Laporan -->
    <div class="card rounded-3 border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="fas fa-list-alt fa-lg text-primary me-3"></i>
                <h4 class="fw-bold mb-0">Daftar Pengaduan</h4>
            </div>
            <div class="d-flex gap-3">
                <div class="dropdown">
                    <button
                        class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center shadow-sm hover-shadow px-3"
                        type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-sort text-primary me-2"></i>Urutkan
                    </button>
                    <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="sortDropdown">
                        <li>
                            <form action="{{ route('responses.index') }}" method="GET">
                                <input type="hidden" name="sort" value="voting_asc">
                                <button type="submit" class="dropdown-item"><i
                                        class="fas fa-sort-amount-down-alt text-primary me-2"></i>Vote Terendah</button>
                            </form>
                        </li>
                        <li>
                            <form action="{{ route('responses.index') }}" method="GET">
                                <input type="hidden" name="sort" value="voting_desc">
                                <button type="submit" class="dropdown-item"><i
                                        class="fas fa-sort-amount-up text-primary me-2"></i>Vote Tertinggi</button>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="dropdown">
                    <button
                        class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center shadow-sm hover-shadow px-3"
                        type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-file-excel text-success me-2"></i>Export (.xlsx)
                    </button>
                    <ul class="dropdown-menu shadow-sm border-0" aria-labelledby="exportDropdown">
                        <li><a class="dropdown-item" href="{{ route('responses.export') }}"><i
                                    class="fas fa-download text-success me-2"></i>Seluruh Data</a></li>
                        <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#exportModal"><i
                                    class="fas fa-calendar-alt text-success me-2"></i>Berdasarkan Tanggal</a></li>
                    </ul>

                    <!-- Modal Input Tanggal -->
                    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title text-dark" id="exportModalLabel"><i
                                            class="fas fa-calendar-alt text-success me-2"></i>Export Berdasarkan Tanggal
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('responses.export') }}" method="GET">
                                        <div class="input-group">
                                            <input type="date" class="form-control" name="date" id="date"
                                                aria-label="Tanggal" aria-describedby="dateAddon" required>
                                            <button class="btn btn-success px-4" type="submit"
                                                id="dateAddon">Export</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-striped mb-0">
                    <thead class="bg-light align-middle">
                        <tr>
                            <th class="px-4 py-3 text-center">Gambar & Pengirim</th>
                            <th class="px-4 py-3">Lokasi & Tanggal</th>
                            <th class="px-4 py-3">Deskripsi</th>
                            <th class="px-4 py-3 text-center">Jumlah Vote</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($reports->isEmpty())
                            <tr>
                                <td class="px-4 py-4 text-center" colspan="5">
                                    <i class="fas fa-exclamation-circle text-warning me-2"></i>
                                    Anda belum memiliki pengaduan.
                                </td>
                            </tr>
                        @else
                            @foreach ($reports as $report)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            @if ($report->image)
                                                <a href="{{ asset('storage/' . $report->image) }}" data-bs-toggle="modal"
                                                    data-bs-target="#imageModal{{ $report->id }}" class="hover-zoom">
                                                    <img src="{{ asset('storage/' . $report->image) }}"
                                                        class="rounded-circle border shadow-sm" width="60"
                                                        height="60" style="object-fit: cover;">
                                                </a>
                                            @else
                                                <div class="d-flex justify-content-center align-items-center rounded-circle border shadow-sm"
                                                    style="width: 60px; height: 60px; background-color: #f8f9fa;">
                                                    <i class="fa-solid fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="ms-3">
                                                <span class="fw-bold text-dark d-block">{{ $report->user->email }}</span>
                                                <small class="text-muted"><i
                                                        class="fa-solid fa-user text-primary me-1"></i>Pengirim</small>
                                            </div>
                                        </div>

                                        <!-- Modal Preview Gambar -->
                                        <div class="modal fade" id="imageModal{{ $report->id }}" tabindex="-1"
                                            aria-labelledby="imageModalLabel{{ $report->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content border-0">
                                                    <div class="modal-header bg-light">
                                                        <h5 class="modal-title" id="imageModalLabel{{ $report->id }}">
                                                            <i class="fas fa-image text-primary me-2"></i>Preview Gambar
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center p-4">
                                                        <img src="{{ asset('storage/' . $report->image) }}"
                                                            alt="Preview" class="img-fluid rounded shadow-sm" width="600" height="600">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="fw-bold text-primary mb-1"><i
                                                class="fas fa-map-marker-alt me-2"></i>{{ ucfirst(strtolower($report->village)) }}
                                        </div>
                                        <div class="small text-muted mb-1">
                                            {{ ucfirst(strtolower($report->subdistrict)) }},
                                            {{ ucfirst(strtolower($report->regency)) }},
                                            {{ ucfirst(strtolower($report->province)) }}</div>
                                        <small class="text-muted d-flex align-items-center">
                                            <i class="far fa-calendar-alt text-primary me-2"></i>
                                            {{ $report->created_at->format('d F Y') }}
                                        </small>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div id="short-desc-{{ $report->id }}">
                                            <p class="mb-0 text-dark">{{ Str::limit($report->description, 50) }}</p>
                                            @if (strlen($report->description) > 50)
                                                <small class="text-primary cursor-pointer hover-shadow"
                                                    onclick="toggleDescription({{ $report->id }})">Baca
                                                    selengkapnya...</small>
                                            @endif
                                        </div>
                                        <div id="full-desc-{{ $report->id }}" style="display: none;">
                                            <p class="mb-0 text-dark">{{ $report->description }}</p>
                                            <small class="text-primary cursor-pointer hover-shadow"
                                                onclick="toggleDescription({{ $report->id }})">Sembunyikan</small>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-info bg-gradient rounded-pill px-3 py-2 shadow-sm">
                                            <i class="fas fa-thumbs-up me-1"></i>{{ $report->voting }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if ($report->responses->isNotEmpty() && $report->responses->first()->response_status == 'DONE')
                                            <div class="text-success d-flex align-items-center justify-content-center">
                                                <i class="fas fa-check-circle me-2"></i>
                                                <span class="fw-bold">Selesai</span>
                                            </div>
                                        @elseif ($report->responses->isNotEmpty() && $report->responses->first()->response_status == 'REJECT')
                                            <div class="text-danger d-flex align-items-center justify-content-center">
                                                <i class="fas fa-times-circle me-2"></i>
                                                <span class="fw-bold">Ditolak</span>
                                            </div>
                                        @else
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-outline-primary btn-sm dropdown-toggle d-flex align-items-center mx-auto"
                                                    type="button" id="actionMenu" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fas fa-cog me-2"></i>Aksi
                                                </button>
                                                <ul class="dropdown-menu shadow border-0" aria-labelledby="actionMenu">
                                                    <li>
                                                        @if ($report->responses->isNotEmpty() && $report->responses->first()->response_status == 'ON_PROCESS')
                                                            <a href="{{ route('responses.show', $report->responses->first()->id) }}"
                                                                class="dropdown-item text-success d-flex align-items-center">
                                                                <i class="fas fa-eye me-2"></i>
                                                                Lihat Progress
                                                            </a>
                                                        @else
                                                            <a class="dropdown-item d-flex align-items-center"
                                                                href="#" data-bs-toggle="modal"
                                                                data-bs-target="#responseModal{{ $report->id }}">
                                                                <i class="fas fa-info-circle me-2"></i>Tindak lanjut
                                                            </a>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </td>

                                    <!-- Modal Tindak Lanjut -->
                                    <div class="modal fade" id="responseModal{{ $report->id }}" tabindex="-1"
                                        aria-labelledby="responseModalLabel{{ $report->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0">
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title" id="responseModalLabel{{ $report->id }}">
                                                        <i class="fas fa-reply text-primary me-2"></i>Tindak Lanjut
                                                        Pengaduan
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <form action="{{ route('responses.store', $report->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="mb-4">
                                                            <label class="form-label fw-bold">Status Tanggapan</label>
                                                            <select class="form-select shadow-sm" name="response_status"
                                                                required>
                                                                <option value="REJECT">REJECT</option>
                                                                <option value="ON_PROCESS">ON PROCESS</option>
                                                            </select>
                                                        </div>
                                                        <div class="text-end">
                                                            <button type="button" class="btn btn-light me-2 hover-shadow"
                                                                data-bs-dismiss="modal">
                                                                <i class="fas fa-times me-2"></i>Tutup
                                                            </button>
                                                            <button type="submit"
                                                                class="btn btn-primary px-4 hover-shadow">
                                                                <i class="fas fa-save me-2"></i>Simpan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .hover-shadow:hover {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.3s ease;
        }

        .hover-zoom:hover img {
            transform: scale(1.1);
            transition: all 0.3s ease;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endsection

@push('script')
    <script>
        function toggleDescription(id) {
            var shortDesc = document.getElementById('short-desc-' + id);
            var fullDesc = document.getElementById('full-desc-' + id);

            if (fullDesc.style.display === 'none') {
                shortDesc.style.display = 'none';
                fullDesc.style.display = 'block';
            } else {
                shortDesc.style.display = 'block';
                fullDesc.style.display = 'none';
            }
        }
    </script>
@endpush
