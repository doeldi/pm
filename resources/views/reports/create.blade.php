@extends('layouts.layout')

@section('content')
    <form method="POST" action="{{ route('report.store') }}" enctype="multipart/form-data">
        @csrf
        @if (Session::get('success'))
            <div class="alert alert-success"> {{ Session::get('success') }} </div>
        @endif
        @if ($errors->any())
            <ul class="alert alert-danger p-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="province">Provinsi</label>
                    <select class="form-control" id="province" name="province">
                        <option value="">Pilih Provinsi</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="regency">Kota/Kabupaten</label>
                    <select class="form-control" id="regency" name="regency">
                        <option value="">Pilih Kota/Kabupaten</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="district">Kecamatan</label>
                    <select class="form-control" id="district" name="subdistrict">
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="village">Desa/Kelurahan</label>
                    <select class="form-control" id="village" name="village">
                        <option value="">Pilih Desa/Kelurahan</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="report_type">Tipe Laporan</label>
                    <select class="form-control" id="report_type" name="type">
                        <option value="">Pilih Tipe Laporan</option>
                        <option value="KEJAHATAN">Kejahatan</option>
                        <option value="PEMBANGUNAN">Pembangunan</option>
                        <option value="SOSIAL">Sosial</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label for="image">Gambar Pendukung</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="description">Detail Keluhan</label>
            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
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
                console.log('Selected province ID:', provinceId); // Debugging

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
                console.log('Selected regency ID:', regencyId); // Debugging

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
                console.log('Selected district ID:', districtId); // Debugging

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
 