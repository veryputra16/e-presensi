<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
</head>

<body>

    <div class="container my-5">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="text-center">{{ env('APP_NAME') }}</h4>
                <table class="table table-borderless">
                    <tr>
                        <td width="150">Nama Kegiatan</td>
                        <td width="20">:</td>
                        <td>{{ $presence->nama_kegiatan }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Kegiatan</td>
                        <td>:</td>
                        <td>{{ date('d F Y', strtotime($presence->tgl_kegiatan)) }}</td>
                    </tr>
                    <tr>
                        <td>Waktu Mulai</td>
                        <td>:</td>
                        <td>{{ date('H:i', strtotime($presence->tgl_kegiatan)) }}</td>
                    </tr>
                    <tr>
                        <td width="150">Tempat Kegiatan</td>
                        <td width="20">:</td>
                        <td>{{ $presence->tempat_kegiatan }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Form Absensi</h5>
                    </div>
                    <div class="card-body">
                        <form id="form-absen" action="{{ route('absen.save', $presence->id) }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="nama">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama">
                                @error('nama')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="jabatan">Jabatan</label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan">
                                @error('jabatan')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="asal_instansi">Asal Instansi</label>
                                <input type="text" class="form-control" id="asal_instansi" name="asal_instansi">
                                @error('asal_instansi')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="asal_instansi">No. Telp</label>
                                <input type="text" class="form-control" id="no_telp" name="no_telp">
                                @error('no_telp')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Area Tanda Tangan --}}
                            <div class="mb-3">
                                <label for="tanda_tangan">Tanda Tangan</label>
                                
                                <!-- Preview Canvas Kecil (klik untuk buka modal) -->
                                <div class="d-block form-control mb-2 p-2 bg-white" onclick="openSignatureModal()" style="cursor: pointer;">
                                    <canvas id="signature-preview" width="300" height="150"></canvas>
                                </div>

                                <textarea name="signature" id="signature64" class="d-none"></textarea>
                                @error('signature')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                                <button type="button" onclick="clearPreviewCanvas()" class="btn btn-sm btn-secondary">
                                    Clear Preview
                                </button>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Daftar Kehadiran</h5>
                    </div>
                    <div class="card-body">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tanda Tangan -->
    <div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tanda Tangan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body text-center">
            <canvas id="signature-pad" width="600" height="300" style="border:1px solid #ccc; width: 100%; max-width: 700px;"></canvas>
            <div class="mt-3 text-end">
            <button id="clear" class="btn btn-sm btn-warning">Clear</button>
            <button class="btn btn-sm btn-success" data-bs-dismiss="modal" onclick="saveSignature()">Simpan</button>
            </div>
        </div>
        </div>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('js/signature.min.js') }}"></script>


    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

    <script>
        $(function() {
            // // set signature pad width
            // let sig = $('#signature-pad').parent().width();
            // $('#signature-pad').attr('width', sig);

            // // Set canvas color
            // let signaturePad = new SignaturePad(document.getElementById('signature-pad'), {
            //     backgroundColor: 'rgb(255, 255, 255, 0)',
            //     penColor: 'rgb(0, 0, 0)',
            // });

            // // Fill signature to textarea
            // $('canvas').on('mouseup touchend', function() {
            //     $('#signature64').val(signaturePad.toDataURL());
            // });

            // // clear signature
            // $('#clear').on('click', function(e) {
            //     e.preventDefault();
            //     signaturePad.clear();
            //     $('#signature64').val('');
            // });

            // Submit form
            $('#form-absen').on('submit', function() {
                $(this).find('button[type="submit"]').attr('disabled', 'disabled');
            });
        });
    </script>

    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    
    <script>
        let signaturePad;

        function openSignatureModal() {
            const modal = new bootstrap.Modal(document.getElementById('signatureModal'));
            modal.show();

            setTimeout(() => {
                const canvas = document.getElementById('signature-pad');
                resizeCanvas(canvas);

                signaturePad = new SignaturePad(canvas, {
                    backgroundColor: 'rgb(255,255,255)',
                    penColor: 'rgb(0,0,0)',
                });
            }, 200);
        }

        function resizeCanvas(canvas) {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }

        function saveSignature() {
            const dataURL = signaturePad.toDataURL();

            // Tampilkan preview di canvas kecil
            const previewCanvas = document.getElementById('signature-preview');
            const ctx = previewCanvas.getContext('2d');
            const image = new Image();
            image.onload = function () {
                ctx.clearRect(0, 0, previewCanvas.width, previewCanvas.height);
                ctx.drawImage(image, 0, 0, previewCanvas.width, previewCanvas.height);
            };
            image.src = dataURL;

            // Simpan ke hidden textarea untuk dikirim ke backend
            $('#signature64').val(dataURL);
        }

        function clearPreviewCanvas() {
            const canvas = document.getElementById('signature-preview');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            $('#signature64').val('');
        }

        $('#signatureModal').on('shown.bs.modal', function () {
            if (signaturePad) signaturePad.clear();
        });

        $('#clear').on('click', function (e) {
            e.preventDefault();
            if (signaturePad) signaturePad.clear();
        });

        $('#form-absen').on('submit', function () {
            $(this).find('button[type="submit"]').attr('disabled', 'disabled');
        });
    </script>

</body>

</html>
