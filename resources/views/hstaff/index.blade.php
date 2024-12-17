@extends('layouts.layout')

@section('content')
    @if (Session::get('failed'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
            {{ Session::get('failed') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div id="noDataAlert"
        class="alert alert-info d-flex align-items-center {{ $reportsByProvince->isEmpty() ? '' : 'd-none' }}">
        <i class="fas fa-info-circle me-2"></i>
        Tidak ada pengaduan dan response di provinsi ini
    </div>

    @if ($reportsByProvince->isNotEmpty())
        <div class="statistic-container">
            <!-- Statistik -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Pengaduan</h5>
                            <p class="card-text">{{ $statistics['totalReports'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Tanggapan</h5>
                            <p class="card-text">{{ $statistics['totalResponses'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Bar -->
            <div class="mb-4">
                <canvas id="provinceChart" height="100"></canvas>
            </div>
        </div>
    @endif
@endsection

@push('script')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const reportsByProvince = @json($reportsByProvince);
            const responsesByProvince = @json($responsesByProvince);

            const labels = reportsByProvince.map(r => r.province);
            const reportCounts = reportsByProvince.map(r => r.total_reports);
            const responseCounts = labels.map(label => {
                const response = responsesByProvince.find(r => r.province === label);
                return response ? response.total_responses : 0;
            });

            const ctx = document.getElementById('provinceChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Jumlah Pengaduan',
                            data: reportCounts,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                        },
                        {
                            label: 'Jumlah Tanggapan',
                            data: responseCounts,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        });
    </script>
@endpush
