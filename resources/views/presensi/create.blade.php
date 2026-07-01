@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-wrapper {
            position: relative;
            width: 100%;
            overflow: hidden;
            border-radius: 15px;
            background: #111;
        }

        .webcamp {
            width: 100% !important;
            height: auto !important;
            margin: auto;
            border-radius: 15px;
            overflow: hidden;
            transform: scaleX(-1) !important;
            -webkit-transform: scaleX(-1) !important;
            transform-origin: center center;
        }

        .webcamp video,
        .webcamp canvas,
        .webcamp object,
        .webcamp embed {
            display: block;
            width: 100% !important;
            height: auto !important;
            border-radius: 15px;
            transform: none !important;
            -webkit-transform: none !important;
        }

        #face-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
            pointer-events: none;
            z-index: 10;
            transform: scaleX(-1) !important;
            -webkit-transform: scaleX(-1) !important;
            transform-origin: center center;
        }

        #face-status {
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 8px;
        }

        #map {
            height: 200px;
        }

        .jam-digital-malasngoding {
            background-color: #27272783;
            position: absolute;
            top: 72px;
            right: 9px;
            z-index: 9999;
            width: 150px;
            border-radius: 10px;
            padding: 5px;
        }

        .jam-digital-malasngoding p {
            color: #fff;
            font-size: 16px;
            text-align: center;
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('assets/js/face-api.min.js') }}"></script>
@endsection
@section('content')
    @php
        $fotoKaryawan = Auth::guard('karyawan')->user()->foto;
        $fotoReferensi = !empty($fotoKaryawan) ? $fotoKaryawan : null;
    @endphp

    <div class="row" style="margin-top: 70px">
        <div class="col">
            <input type="hidden" id="lokasi">
            <div class="webcam-wrapper">
                <div class="webcamp"></div>
                <canvas id="face-canvas"></canvas>
            </div>
            <div id="face-status" class="alert alert-warning mt-1 mb-1">
                Memuat model face recognition...
            </div>
        </div>
    </div>
    <div class="jam-digital-malasngoding">
        <p>{{ date('d-m-Y') }}</p>
        <p id="jam"></p>
        <p>{{ $jamkerja->nama_jam_kerja }}</p>
        <p>Awal :{{ date('H:i', strtotime($jamkerja->awal_jam_masuk)) }}</p>
        <p>Masuk :{{ date('H:i', strtotime($jamkerja->jam_masuk)) }}</p>
        <p>Akhir :{{ date('H:i', strtotime($jamkerja->akhir_jam_masuk)) }}</p>
    </div>
    <div class="row">
        <div class="col">
            @if ($cek > 0)
                <button disabled id="takeabsen" class="btn btn-danger btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Sudah Absen Hari Ini
                </button>
            @else
                <button id="takeabsen" class="btn btn-primary btn-block" disabled>
                    <ion-icon name="camera-outline"></ion-icon>
                    Menyiapkan Face Recognition...
                </button>
            @endif
        </div>
    </div>
    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>
@endsection

@push('myscript')
    <script type="text/javascript">
        window.onload = function() {
            jam();
        }

        function jam() {
            var e = document.getElementById('jam'),
                d = new Date(),
                h, m, s;
            h = d.getHours();
            m = set(d.getMinutes());
            s = set(d.getSeconds());

            e.innerHTML = h + ':' + m + ':' + s;

            setTimeout('jam()', 1000);
        }

        function set(e) {
            e = e < 10 ? '0' + e : e;
            return e;
        }
    </script>
    <script>
        var image = '';
        var faceMatcher = null;
        var modelsLoaded = false;
        var faceDetectionInterval = null;
        var faceReferenceUrl = @json($fotoReferensi);
        var faceStatus = document.getElementById('face-status');
        var faceCanvas = document.getElementById('face-canvas');
        var takeAbsenButton = document.getElementById('takeabsen');

        Webcam.set({
            width: 640,
            height: 480,
            image_format: 'jpeg',
            jpeg_quality: 80,
            flip_horiz: true
        });

        Webcam.attach('.webcamp');

        initFaceRecognition();

        async function initFaceRecognition() {
            if (!faceReferenceUrl) {
                setFaceStatus('Foto profil belum tersedia. Upload foto profil dulu untuk face recognition.', 'danger');
                return;
            }

            try {
                await Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models')
                ]);

                var referenceImage = await faceapi.fetchImage(faceReferenceUrl);
                var referenceDetection = await faceapi
                    .detectSingleFace(referenceImage, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();

                if (!referenceDetection) {
                    setFaceStatus('Wajah pada foto profil tidak terdeteksi. Ganti foto profil yang lebih jelas.',
                        'danger');
                    return;
                }

                faceMatcher = new faceapi.FaceMatcher(
                    new faceapi.LabeledFaceDescriptors('karyawan', [referenceDetection.descriptor]),
                    0.6
                );
                modelsLoaded = true;
                setFaceStatus('Face recognition siap. Arahkan wajah ke kamera.', 'success');
                enableAbsenButton();
                startFaceDetectionLoop();
            } catch (error) {
                console.error(error);
                setFaceStatus('Gagal memuat face recognition. Pastikan file model tersedia di public/models.',
                    'danger');
            }
        }

        function enableAbsenButton() {
            if (takeAbsenButton && !takeAbsenButton.disabled) {
                return;
            }

            if (takeAbsenButton && takeAbsenButton.classList.contains('btn-primary')) {
                takeAbsenButton.disabled = false;
                takeAbsenButton.innerHTML = '<ion-icon name="camera-outline"></ion-icon> Absen Masuk';
            }
        }

        function setFaceStatus(message, type) {
            faceStatus.innerText = message;
            faceStatus.className = 'alert alert-' + type + ' mt-1 mb-1';
        }

        function getWebcamVideo() {
            return document.querySelector('.webcamp video');
        }

        function startFaceDetectionLoop() {
            var video = getWebcamVideo();

            if (!video || video.readyState < 2) {
                setTimeout(startFaceDetectionLoop, 500);
                return;
            }

            var displaySize = {
                width: video.offsetWidth,
                height: video.offsetHeight
            };

            faceapi.matchDimensions(faceCanvas, displaySize);

            faceDetectionInterval = setInterval(async function() {
                var detections = await faceapi
                    .detectAllFaces(video, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptors();

                var resizedDetections = faceapi.resizeResults(detections, displaySize);
                var context = faceCanvas.getContext('2d');
                context.clearRect(0, 0, faceCanvas.width, faceCanvas.height);

                resizedDetections.forEach(function(detection) {
                    var box = detection.detection.box;
                    var result = faceMatcher.findBestMatch(detection.descriptor);
                    var isMatch = result.label !== 'unknown';

                    context.strokeStyle = isMatch ? '#22c55e' : '#ef4444';
                    context.lineWidth = 4;
                    context.strokeRect(box.x, box.y, box.width, box.height);

                    context.fillStyle = isMatch ? '#22c55e' : '#ef4444';
                    context.font = '16px Arial';
                    context.fillText(isMatch ? 'Wajah cocok' : 'Wajah tidak cocok', box.x, Math.max(box
                        .y - 8, 16));
                });
            }, 700);
        }

        async function verifyFace(capturedImage) {
            if (!modelsLoaded || !faceMatcher) {
                Swal.fire({
                    title: 'Face Recognition Belum Siap',
                    text: 'Tunggu sampai model face recognition selesai dimuat.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            var img = await faceapi.fetchImage(capturedImage);

            var detection = await faceapi
                .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions({
                    inputSize: 416,
                    scoreThreshold: 0.4
                }))
                .withFaceLandmarks()
                .withFaceDescriptor();

            if (!detection) {
                Swal.fire({
                    title: 'Wajah Tidak Terdeteksi',
                    text: 'Pastikan wajah terlihat jelas di kamera.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            var result = faceMatcher.findBestMatch(detection.descriptor);

            if (result.label === 'unknown') {
                Swal.fire({
                    title: 'Wajah Tidak Cocok',
                    text: 'Wajah tidak sesuai dengan foto profil karyawan.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            return true;
        }

        var lokasi = document.getElementById('lokasi');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
        }

        function successCallback(position) {
            lokasi.value = position.coords.latitude + "," + position.coords.longitude;
            var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
            var lokasi_kantor = "{{ $lok_kantor->lokasi_kantor }}";
            var lok = lokasi_kantor.split(',');
            var lat_kantor = parseFloat(lok[0]);
            var long_kantor = parseFloat(lok[1]);
            var radius = {{ $lok_kantor->radius }};
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
            var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
            var circle = L.circle([lat_kantor, long_kantor], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius
            }).addTo(map);
        }

        function errorCallback() {

        }

        $('#takeabsen').click(async function(e) {
            e.preventDefault();

            Webcam.snap(function(uri) {
                image = uri;
            });

            var faceValid = await verifyFace(image);

            if (!faceValid) {
                return;
            }

            var lokasi = $('#lokasi').val();

            $.ajax({
                type: 'POST',
                url: '/presensi/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: lokasi
                },
                cache: false,
                success: function(respond) {
                    if (respond == 0) {
                        Swal.fire({
                            title: 'Berhasil !',
                            text: 'Wajah cocok. Terimakasih, Selamat bekerja !',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        })
                        setTimeout("location.href='/dashboard'", 3000);
                    } else {
                        var pesan = respond.split('|');
                        if (pesan[0] == 'error') {
                            Swal.fire({
                                title: 'Error !',
                                text: pesan[1],
                                icon: 'error',
                                confirmButtonText: 'OK'
                            })
                        }
                    }
                }
            });
        });
    </script>
@endpush
