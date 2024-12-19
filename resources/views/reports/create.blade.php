@extends('layouts.layout')

@section('content')
    <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data">
        @csrf
        @if (Session::get('success'))
            <div class="alert alert-success"> {{ Session::get('success') }} </div>
        @endif
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="province">Provinsi</label>
                    <select class="form-control @error('province') is-invalid @enderror" id="province" name="province">
                        <option value="">Pilih Provinsi</option>
                    </select>
                    @error('province')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="regency">Kota/Kabupaten</label>
                    <select class="form-control @error('regency') is-invalid @enderror" id="regency" name="regency">
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                    @error('regency')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="district">Kecamatan</label>
                    <select class="form-control @error('subdistrict') is-invalid @enderror" id="district"
                        name="subdistrict">
                        <option value="">Pilih Kecamatan</option>
                    </select>
                    @error('subdistrict')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="village">Desa/Kelurahan</label>
                    <select class="form-control @error('village') is-invalid @enderror" id="village" name="village">
                        <option value="">Pilih Desa/Kelurahan</option>
                    </select>
                    @error('village')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="report_type">Tipe Laporan</label>
                    <select class="form-control @error('type') is-invalid @enderror" id="report_type" name="type">
                        <option value="">Pilih Tipe Laporan</option>
                        <option value="KEJAHATAN" {{ old('type') == 'KEJAHATAN' ? 'selected' : '' }}>Kejahatan</option>
                        <option value="PEMBANGUNAN" {{ old('type') == 'PEMBANGUNAN' ? 'selected' : '' }}>Pembangunan
                        </option>
                        <option value="SOSIAL" {{ old('type') == 'SOSIAL' ? 'selected' : '' }}>Sosial</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="image">Gambar Pendukung</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                        name="image" accept="image/*" value="{{ old('image') }}">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="description">Detail Keluhan</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                rows="4">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- tambahkan checkbox untuk statement --}}
        <div class="form-group mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="statement" name="statement" value="1">
                <small class="form-check-label" for="statement">
                    Saya menyatakan bahwa semua informasi yang saya berikan adalah benar dan akurat.
                </small>
                <div class="invalid-feedback">
                    Harap centang checkbox ini
                </div>
            </div>
        </div>

        <div class="form-group mb-0 text-center">
            <button type="submit" class="btn btn-primary">
                Submit report
            </button>
        </div>
    </form>
@endsection

@push('styles')
    <style>
        body {
            align-items: center;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
            transition: all 0.3s ease;
        }

        .form-control {
            border: 1px solid #cce5ff;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s ease;
        }

        label {
            color: #0b5ed7;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, #0b5ed7, #0077cc);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #0b5ed7, #0077cc);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.3);
        }

        select.form-control {
            background-color: #fff;
            cursor: pointer;
        }

        select.form-control:hover {
            border-color: #0d6efd;
            background-color: #f8fbff;
        }

        h2 {
            color: #0b5ed7;
            margin-bottom: 40px;
            text-align: center;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            padding-bottom: 15px;
        }

        .col-md-8 {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-group {
            margin-bottom: 25px !important;
        }

        input[type="file"].form-control {
            padding: 8px;
            height: auto;
        }
    </style>
@endpush

@push('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Load provinces
            $.ajax({
                url: 'https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
                type: 'GET',
                success: function(response) {
                    console.log('Provinces:', response); // Debugging
                    response.forEach(function(province) {
                        $('#province').append(
                            `<option value="${province.name}" data-id="${province.id}">${province.name}</option>`
                        );
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch provinces:', error);
                }
            });

            // Load regencies when province is selected
            $('#province').change(function() {
                const provinceId = $(this).find(':selected').data('id'); // Get provinceId

                $('#regency').empty().append('<option value="">Pilih Kota/Kabupaten</option>');
                $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
                $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');

                if (provinceId) {
                    $.ajax({
                        url: `https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`,
                        type: 'GET',
                        success: function(response) {
                            console.log('Regencies:', response); // Debugging
                            response.forEach(function(regency) {
                                $('#regency').append(
                                    `<option value="${regency.name}" data-id="${regency.id}">${regency.name}</option>`
                                );
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Failed to fetch regencies:', error);
                        }
                    });
                }
            });

            // Load districts when regency is selected
            $('#regency').change(function() {
                const regencyId = $(this).find(':selected').data('id'); // Get regencyId

                $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
                $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');

                if (regencyId) {
                    $.ajax({
                        url: `https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`,
                        type: 'GET',
                        success: function(response) {
                            console.log('Districts:', response); // Debugging
                            response.forEach(function(district) {
                                $('#district').append(
                                    `<option value="${district.name}" data-id="${district.id}">${district.name}</option>`
                                );
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Failed to fetch districts:', error);
                        }
                    });
                }
            });

            // Load villages when district is selected
            $('#district').change(function() {
                const districtId = $(this).find(':selected').data('id'); // Get districtId

                $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');

                if (districtId) {
                    $.ajax({
                        url: `https://www.emsifa.com/api-wilayah-indonesia/api/villages/${districtId}.json`,
                        type: 'GET',
                        success: function(response) {
                            console.log('Villages:', response); // Debugging
                            response.forEach(function(village) {
                                $('#village').append(
                                    `<option value="${village.name}">${village.name}</option>`
                                );
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Failed to fetch villages:', error);
                        }
                    });
                }
            });
        });
    </script>
@endpush
