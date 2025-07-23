@extends('layouts.main')

@section('content')
<div class="container">

    <!-- Header -->
    <div class="text-center mb-4">
        <img src="{{ asset('images/logo-diskominfo.png') }}" alt="Logo" style="height: 200px;">
        <h2 class="mt-3">Selamat datang, {{ Auth::user()->name }}!</h2>
        <p class="text-muted">Dashboard Ringkasan Kehadiran Kegiatan</p>
    </div>

    <!-- Card Grafik Kehadiran -->
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Grafik Kehadiran Peserta per Kegiatan</h5>
            <div>
                <label for="filterTahun" class="mr-2 mb-0">Pilih Tahun:</label>
                <select id="filterTahun" class="form-control form-control-sm d-inline-block w-auto">
                    @php
                        $currentYear = now()->year;
                    @endphp
                    @for ($year = $currentYear; $year >= $currentYear - 10; $year--)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="card-body">
            <canvas id="myChart" height="100"></canvas>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<!-- Tambahkan CDN Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let chart; // global variable for Chart instance

    function loadChart(tahun) {
        fetch(`/chart-data?tahun=${tahun}`)
            .then(response => response.json())
            .then(data => {
                console.log(data);

                const ctx = document.getElementById('myChart').getContext('2d');

                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Jumlah Peserta',
                            data: data.counts,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                precision: 0
                            }
                        }
                    }
                });
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tahunSelect = document.getElementById('filterTahun');
        loadChart(tahunSelect.value);

        tahunSelect.addEventListener('change', function () {
            loadChart(this.value);
        });
    });
</script>
@endpush
