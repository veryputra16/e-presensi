@extends('layouts.main')

@section('title', 'QR Code Absen - ' . $presence->nama_kegiatan)

@section('content')
<div class="container mt-4 text-center">
    <h4>QR Code untuk: <strong>{{ $presence->nama_kegiatan }}</strong></h4>
    <p>Scan untuk mengisi absen mandiri:</p>

    <div class="my-4">
        {!! QrCode::size(250)->generate($linkAbsen) !!}
    </div>

    <p><strong>Link:</strong> <a href="{{ $linkAbsen }}" target="_blank">{{ $linkAbsen }}</a></p>

    <a href="{{ route('presence.qrcode.download', $presence->id) }}" class="btn btn-success">
        <i class="bi bi-download"></i> Download QR Code
    </a>
    <a href="{{ route('presence.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection

@push('scripts')
<!-- CDN html2canvas -->
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

<script>
    function downloadQRCodePNG() {
        const qrElement = document.getElementById('qr-area');
        html2canvas(qrElement).then(canvas => {
            const link = document.createElement('a');
            link.download = 'qrcode-{{ Str::slug($presence->nama_kegiatan) }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }
</script>
@endpush