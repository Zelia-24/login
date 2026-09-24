<?php
require_once '../database/koneksi.php';

$nim_login  = $_SESSION['username'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner Presensi Mahasiswa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        /* Tampilan dasar wadah kamera */
        #qr-reader {
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }

        /* Mengubah warna garis/sudut kotak fokus scan di dalam kamera menjadi HIJAU */
        #qr-reader__scan_region div {
            border-color: #28a745 !important;
        }

        /* Mengubah elemen SVG/border indikator scan bawaan menjadi hijau */
        #qr-reader__scan_region img,
        #qr-reader__scan_region svg {
            filter: invert(48%) sepia(79%) saturate(2476%) hue-rotate(86deg) brightness(118%) contrast(119%);
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-primary shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="card-title mb-0">Scanner Presensi Mahasiswa</h5>
                </div>
                <div class="card-body">
                    <!-- Area Kamera -->
                    <div id="reader"></div>

                    <!-- Notifikasi Hasil Presensi -->
                    <div id="scan-result" class="alert alert-success mt-3 text-center" style="display: none;">
                        <i class="fas fa-check-circle"></i> 
                        <span id="result-text"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        console.log(`Scan result: ${decodedText}`);

        document.getElementById('scan-result').style.display = 'block';
        document.getElementById('result-text').innerText = 'QR Code Terdeteksi! Memproses presensi...';

        html5QrcodeScanner.clear().then(() => {
            window.location.href = 'proses_presensi.php?id_pertemuan=' + encodeURIComponent(decodedText);
        }).catch((error) => {
            window.location.href = 'proses_presensi.php?id_pertemuan=' + encodeURIComponent(decodedText);
        });
    }

    function onScanError(errorMessage) {
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { fps: 10, qrbox: { width: 250, height: 250 } }
    );
    
    html5QrcodeScanner.render(onScanSuccess, onScanError);
</script>
</body>
</html>