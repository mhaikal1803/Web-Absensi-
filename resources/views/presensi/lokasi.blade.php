@extends('layouts.presensi')

@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/dashboard" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Halaman Lokasi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        #map {
            width: 100%;
            height: 220px;
            border-radius: 10px;
        }

        .lokasi-card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .lokasi-first-section {
            margin-top: 70px !important;
        }

        .lokasi-status {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px;
            border-radius: 10px;
            background: #eef6ff;
            border: 1px solid #bfdbfe;
        }

        .lokasi-status-icon {
            width: 45px;
            height: 45px;
            min-width: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1e74fd;
            color: #ffffff;
            font-size: 25px;
        }

        .lokasi-status h4 {
            margin: 0 0 4px;
            font-size: 15px;
            font-weight: 700;
        }

        .lokasi-status p {
            margin: 0;
            font-size: 12px;
            color: #6c757d;
            line-height: 1.4;
        }

        .lokasi-detail-row {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            padding: 12px 0;
            border-bottom: 1px solid #edf0f5;
            font-size: 13px;
        }

        .lokasi-detail-row:last-child {
            border-bottom: 0;
        }

        .lokasi-detail-row span {
            color: #6c757d;
        }

        .lokasi-detail-row strong {
            text-align: right;
            color: #27173e;
        }

        .btn-lokasi {
            border-radius: 8px;
            font-weight: 700;
            padding: 12px 16px;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('content')
    @php
        $lokasiKantor = $lok_kantor->lokasi_kantor ?? '-6.200000,106.816666';
        $radiusKantor = $lok_kantor->radius ?? 100;
        $koordinatKantor = explode(',', $lokasiKantor);
        $latitudeKantor = trim($koordinatKantor[0] ?? '-6.200000');
        $longitudeKantor = trim($koordinatKantor[1] ?? '106.816666');
    @endphp

    <!-- App Capsule -->
    <div id="appCapsule">
        <input type="hidden" id="lokasi">

        <div class="section mt-2 lokasi-first-section">
            <div class="card lokasi-card">
                <div class="card-body">
                    <h3 class="mb-2">Lokasi Presensi</h3>
                    <div class="lokasi-status mb-2">
                        <div class="lokasi-status-icon">
                            <ion-icon name="location"></ion-icon>
                        </div>
                        <div>
                            <h4>Lokasi kamu terdeteksi</h4>
                            <p>Pastikan kamu berada di area kerja sebelum melakukan presensi masuk atau pulang.</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success btn-block btn-lokasi" onclick="getLocation()">
                        <ion-icon name="navigate-outline"></ion-icon>
                        Gunakan Lokasi Saat Ini
                    </button>
                    <button type="button" class="btn btn-primary btn-block btn-lokasi mt-1" onclick="openGoogleMaps()">
                        <ion-icon name="map-outline"></ion-icon>
                        Buka di Google Maps
                    </button>
                </div>
            </div>
        </div>

        <div class="section mt-2">
            <div class="card lokasi-card">
                <div class="card-body p-0">
                    <div id="map"></div>
                </div>
            </div>
        </div>

        <div class="section mt-2" style="margin-bottom: 100px;">
            <div class="card lokasi-card">
                <div class="card-body">
                    <h3 class="mb-2">Detail Lokasi</h3>
                    <div class="lokasi-detail-row">
                        <span>Nama tempat</span>
                        <strong>Magi Coffe</strong>
                    </div>
                    <div class="lokasi-detail-row">
                        <span>Alamat</span>
                        <strong>Jl. Amal Luhur No.75</strong>
                    </div>
                    <div class="lokasi-detail-row">
                        <span>Lokasi kantor</span>
                        <strong>{{ $lokasiKantor }}</strong>
                    </div>
                    <div class="lokasi-detail-row">
                        <span>Radius kantor</span>
                        <strong>{{ $radiusKantor }} meter</strong>
                    </div>
                    <div class="lokasi-detail-row">
                        <span>Lokasi kamu</span>
                        <strong id="lokasi-detail">Menunggu izin lokasi</strong>
                    </div>
                    <div class="lokasi-detail-row">
                        <span>Status area</span>
                        <strong class="text-muted" id="status-area">Belum diperiksa</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->
@endsection

@push('myscript')
    <script>
        var lokasi = document.getElementById('lokasi');
        var latKantor = parseFloat(@json($latitudeKantor));
        var longKantor = parseFloat(@json($longitudeKantor));
        var radius = parseFloat(@json($radiusKantor));
        var currentLatitude = latKantor;
        var currentLongitude = longKantor;
        var userMarker = null;

        var map = L.map('map').setView([latKantor, longKantor], 18);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        L.marker([latKantor, longKantor]).addTo(map).bindPopup('Lokasi kantor');

        L.circle([latKantor, longKantor], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: radius
        }).addTo(map);

        setTimeout(function() {
            map.invalidateSize();
        }, 300);

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        }

        function successCallback(position) {
            currentLatitude = position.coords.latitude;
            currentLongitude = position.coords.longitude;
            lokasi.value = currentLatitude + ',' + currentLongitude;

            document.getElementById('lokasi-detail').innerText = lokasi.value;
            var jarak = hitungJarak(latKantor, longKantor, currentLatitude, currentLongitude);
            var statusArea = document.getElementById('status-area');

            if (jarak <= radius) {
                statusArea.innerText = 'Dalam radius (' + Math.round(jarak) + ' meter)';
                statusArea.className = 'text-success';
            } else {
                statusArea.innerText = 'Di luar radius (' + Math.round(jarak) + ' meter)';
                statusArea.className = 'text-danger';
            }

            if (userMarker != null) {
                map.removeLayer(userMarker);
            }

            userMarker = L.marker([currentLatitude, currentLongitude]).addTo(map).bindPopup('Lokasi kamu').openPopup();

            var bounds = L.latLngBounds([
                [latKantor, longKantor],
                [currentLatitude, currentLongitude]
            ]);

            map.fitBounds(bounds, {
                padding: [40, 40],
                maxZoom: 18
            });
            map.invalidateSize();
        }

        function hitungJarak(lat1, lon1, lat2, lon2) {
            var earthRadius = 6371000;
            var dLat = toRadian(lat2 - lat1);
            var dLon = toRadian(lon2 - lon1);
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(toRadian(lat1)) * Math.cos(toRadian(lat2)) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return earthRadius * c;
        }

        function toRadian(value) {
            return value * Math.PI / 180;
        }

        function errorCallback() {
            document.getElementById('status-area').innerText = 'Izin lokasi ditolak / tidak tersedia';
            document.getElementById('status-area').className = 'text-danger';
            map.invalidateSize();
        }

        function getLocation() {
            if (!navigator.geolocation) {
                alert('Browser kamu belum mendukung fitur lokasi.');
                return;
            }

            navigator.geolocation.getCurrentPosition(successCallback, errorCallback, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        }

        function openGoogleMaps() {
            window.open('https://www.google.com/maps?q=' + currentLatitude + ',' + currentLongitude, '_blank');
        }
    </script>
@endpush
