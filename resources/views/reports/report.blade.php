@extends('layouts.layout')

@section('content')
    <div class="row g-4">
        <!-- Bagian Kiri - Informasi -->
        <div class="col-lg-4">
            <div class="info-section shadow bg-light">
                <h4 class="mb-4">Informasi Pembuatan Pengaduan</h4>
                <ul class="list-unstyled">
                    <li class="mb-3"><i class="fas fa-check-circle me-2 text-primary"></i>Pilih provinsi tempat kejadian
                    </li>
                    <li class="mb-3"><i class="fas fa-check-circle me-2 text-primary"></i>Isi detail pengaduan dengan jelas
                    </li>
                    <li class="mb-3"><i class="fas fa-check-circle me-2 text-primary"></i>Sertakan bukti pendukung jika ada
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
                <div class="form-group mb-4">
                    <label for="provinceDropdown" class="form-label h5">Pilih Provinsi:</label>
                    <select id="provinceDropdown" class="form-select">
                        <option value="">Semua Provinsi</option>
                    </select>
                </div>

                <div class="mt-4" id="reportContainer">
                    <div id="noDataAlert" class="alert alert-info d-flex align-items-center d-none">
                        <i class="fas fa-info-circle me-2"></i>
                        Tidak ada pengaduan untuk provinsi yang dipilih.
                    </div>

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
                                            <i class="fas fa-info-circle me-1"></i> Detail
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
                                        </div>

                                        <!-- Form Vote dan Unvote -->
                                        <div class="d-flex gap-2">
                                            <form action="{{ route('report.vote', $report->id) }}" method="POST">
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

            $('#provinceDropdown').change(function() {
                var selectedProvinceName = $(this).val();
                var noDataAlert = $('#noDataAlert');
                
                if (selectedProvinceName === '') {
                    $('.report-card').show();
                    noDataAlert.addClass('d-none');
                } else {
                    $('.report-card').hide();
                    var filteredCards = $('.report-card[data-province-name="' + selectedProvinceName + '"]');
                    
                    if (filteredCards.length > 0) {
                        filteredCards.show();
                        noDataAlert.addClass('d-none');
                    } else {
                        noDataAlert.removeClass('d-none');
                    }
                }
            });
        });
    </script>
@endpush