@extends('layouts.main')

@section('content')
    <div class="container my-4">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col">
                        <h4 class="card-title">
                            Daftar Kegiatan
                        </h4>
                    </div>
                    <div class="col text-end">
                        <a href="{{ route('presence.create') }}" class="btn btn-primary">
                            Tambah Data
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- Filter tehun --}}
                {{-- <div class="mb-3">
                    <label for="filterTahun" class="form-label">Filter Tahun:</label>
                    <select id="filterTahun" class="form-select w-auto d-inline-block">
                        <option value="">-- Semua Tahun --</option>
                        @php
                            $currentYear = now()->year;
                        @endphp
                        @for ($year = $currentYear; $year >= $currentYear - 10; $year--)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div> --}}
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection

@push('js')

    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');

            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    success: function(data) {
                        window.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }
        })
    </script>
{{-- 
<script type="module">
    const tableId = "{{ $dataTable->getTableAttribute('id') }}";
    const tahunSelect = document.getElementById('filterTahun');

    tahunSelect.addEventListener('change', function () {
        window.LaravelDataTables[tableId].ajax.reload();
    });

    // Tambah parameter tahun ke AJAX request
    $.fn.dataTable.ext.errMode = 'throw'; // Debug mode
    $.fn.dataTable.ext.ajax = $.fn.dataTable.ext.ajax || {};

    $.fn.dataTable.ext.ajax.data = function (d) {
        d.tahun = tahunSelect.value;
    };
</script> --}}
@endpush
