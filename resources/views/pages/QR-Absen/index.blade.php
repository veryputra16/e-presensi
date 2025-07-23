<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $presence->nama_kegiatan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-white transition duration-500">

<div class="max-w-xl mx-auto mt-10 p-6 bg-white dark:bg-gray-800 shadow-2xl rounded-xl relative">

    <!-- Logo + Judul -->
    <div class="text-center mb-6">
        <img src="{{ asset('images/logo-diskominfo.png') }}" alt="Logo" class="mx-auto h-20 mb-2" style="height: 200px;" />
        <h1 class="text-xl font-bold mb-4">Daftar Hadir - {{ $presence->nama_kegiatan }}</h1>
        <p id="jamSekarang" class="text-sm text-gray-600 dark:text-gray-300 mt-1"></p>
    </div>

    <!-- Form -->
    <form id="bukuTamuForm" action="{{ route('absen.save', $presence->id) }}" method="POST" onsubmit="return handleFormSubmit(event)">
        @csrf
        <input type="hidden" name="signature" id="signature">

        <div>
            <label class="block font-semibold">Nama Lengkap</label>
            <input type="text" name="nama" id="nama" class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700" required />
        </div>
        <div>
            <label class="block font-semibold">Jabatan</label>
            <input type="text" name="jabatan" class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700" required />
        </div>
        <div>
            <label class="block font-semibold">Instansi/Asal</label>
            <input type="text" name="asal_instansi" id="instansi" class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700" required />
        </div>
        <div>
            <label class="block font-semibold">Nomor Telepon</label>
            <input type="text" name="no_telp" class="w-full mt-1 p-2 border rounded-md dark:bg-gray-700" required />
        </div>

        <!-- Canvas Preview -->
        <div>
            <label class="block font-semibold">Tanda Tangan</label>
            <div class="w-full">
                <canvas id="signature-preview" class="border rounded-md bg-white cursor-pointer w-full" height="200" onclick="openSignatureModal()"></canvas>
            </div>
            <button type="button" onclick="clearPreviewCanvas()" class="mt-2 bg-gray-300 text-sm px-2 py-1 rounded hover:bg-gray-400">Bersihkan</button>
        </div>

        <button type="submit" class="w-full mt-4 bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-300">Kirim</button>

        <p class="text-xs text-center mt-2 text-gray-500 dark:text-gray-400">✍️ Klik kotak tanda tangan untuk menggambar menggunakan mouse atau jari.</p>
    </form>
</div>

<!-- Modal Zoom Tanda Tangan -->
<div id="signatureModal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-3xl w-full text-center">
        <h2 class="text-lg font-semibold mb-2">Tanda Tangan (Klik dan Tulis)</h2>

        <!-- Tambahkan wrapper ini untuk center canvas -->
        <div class="flex justify-center">
            <canvas id="signature-pad-modal" class="border rounded-md bg-white w-full max-w-[700px]" height="300"></canvas>
        </div>

        <div class="mt-3 flex justify-center gap-4">
            <button onclick="clearModalCanvas()" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Bersihkan</button>
            <button onclick="saveSignature()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            <button onclick="closeSignatureModal()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Batal</button>
        </div>
    </div>
</div>

<script>

    // Tampilkan jam
    setInterval(() => {
        const now = new Date();
        document.getElementById('jamSekarang').textContent = "📅 " + now.toLocaleString('id-ID');
    }, 1000);

    function resizeCanvas(canvas) {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    // Modal logic
    function openSignatureModal() {
        document.getElementById('signatureModal').classList.remove('hidden');

        setTimeout(() => {
            resizeCanvas(modalCanvas);
            modalCtx.clearRect(0, 0, modalCanvas.width, modalCanvas.height);
            modalCtx.beginPath();
        }, 200);
    }
    // function openSignatureModal() {
    //     document.getElementById('signatureModal').classList.remove('hidden');
    // }

    function closeSignatureModal() {
        document.getElementById('signatureModal').classList.add('hidden');
        modalCtx.beginPath(); // reset path
    }

    function clearPreviewCanvas() {
        const canvas = document.getElementById('signature-preview');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById('signature').value = '';
    }

    function clearModalCanvas() {
        modalCtx.clearRect(0, 0, modalCanvas.width, modalCanvas.height);
        modalCtx.beginPath();
    }

    function saveSignature() {
        const image = modalCanvas.toDataURL("image/png");
        const previewCanvas = document.getElementById('signature-preview');
        const ctx = previewCanvas.getContext('2d');

        const img = new Image();
        img.onload = function () {
            ctx.clearRect(0, 0, previewCanvas.width, previewCanvas.height);
            ctx.drawImage(img, 0, 0, previewCanvas.width, previewCanvas.height);
        };
        img.src = image;

        document.getElementById('signature').value = image;
        closeSignatureModal();
    }

    // Signature drawing setup
    const modalCanvas = document.getElementById('signature-pad-modal');
    const modalCtx = modalCanvas.getContext('2d');
    let isDrawing = false;

    function getPos(e) {
        const rect = modalCanvas.getBoundingClientRect();
        if (e.touches) {
            return {
                x: e.touches[0].clientX - rect.left,
                y: e.touches[0].clientY - rect.top
            };
        } else {
            return {
                x: e.clientX - rect.left,
                y: e.clientY - rect.top
            };
        }
    }

    function startDraw(e) {
        e.preventDefault();
        isDrawing = true;
        const pos = getPos(e);
        modalCtx.beginPath();
        modalCtx.moveTo(pos.x, pos.y);
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        const pos = getPos(e);
        modalCtx.lineWidth = 2;
        modalCtx.lineCap = "round";
        modalCtx.strokeStyle = "#000";
        modalCtx.lineTo(pos.x, pos.y);
        modalCtx.stroke();
    }

    function endDraw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        isDrawing = false;
    }

    // Mouse events
    modalCanvas.addEventListener("mousedown", startDraw);
    modalCanvas.addEventListener("mousemove", draw);
    modalCanvas.addEventListener("mouseup", endDraw);
    modalCanvas.addEventListener("mouseout", endDraw);

    // Touch events
    modalCanvas.addEventListener("touchstart", startDraw);
    modalCanvas.addEventListener("touchmove", draw);
    modalCanvas.addEventListener("touchend", endDraw);
    modalCanvas.addEventListener("touchcancel", endDraw);

    // Form submit
    function handleFormSubmit(e) {
        const signatureVal = document.getElementById('signature').value;
        if (!signatureVal) {
            alert("Mohon isi tanda tangan terlebih dahulu.");
            return false;
        }

        document.getElementById('successMsg').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('successMsg').classList.add('hidden');
        }, 3000);

        return true;
    }
</script>

    <!-- SweetAlert Success from Laravel -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

</body>
</html>
