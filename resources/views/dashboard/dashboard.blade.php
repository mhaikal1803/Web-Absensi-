@extends('layouts.presensi')
@section('content')
    <style>
        .logout {
            position: absolute;
            top: 8px;
            right: 8px;
            font-size: 30px;
            text-decoration: none;
            color: #ffffff;
        }

        .logout:hover {
            color: #ffffff;
        }
    </style>
    <!-- App Capsule -->
    <div id="appCapsule">
        <div class="section" id="user-section">
            <a href="/proseslogout" class="logout">
                <ion-icon name="exit-outline"></ion-icon>
            </a>
            <div id="user-detail">
                <div class="avatar">
                    @if (!empty(Auth::guard('karyawan')->user()->foto))
                        @php
                            $foto = Auth::guard('karyawan')->user()->foto;
                            $path = \Illuminate\Support\Str::startsWith($foto, 'http')
                                ? $foto
                                : url(Storage::url('uploads/karyawan/' . $foto));
                        @endphp
                        <img src="{{ $path }}" alt="avatar" class="imaged w64"
                            style="height:64px; object-fit:cover">
                    @else
                        <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                    @endif
                </div>
                <div id="user-info">
                    <h2 id="user-name">{{ Auth::guard('karyawan')->user()->nama_lengkap }}</h2>
                    <span id="user-role">{{ Auth::guard('karyawan')->user()->jabatan }}</span>
                </div>
            </div>
        </div>

        <div class="section" id="menu-section">
            <div class="card">
                <div class="card-body text-center">
                    <div class="list-menu">
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="/editprofile" class="green" style="font-size: 40px;">
                                    <ion-icon name="person-sharp"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Profil</span>
                            </div>
                        </div>

                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="/presensi/history" class="warning" style="font-size: 40px;">
                                    <ion-icon name="document-text"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                <span class="text-center">Histori</span>
                            </div>
                        </div>
                        <div class="item-menu text-center">
                            <div class="menu-icon">
                                <a href="/presensi/lokasi" class="orange" style="font-size: 40px;">
                                    <ion-icon name="location"></ion-icon>
                                </a>
                            </div>
                            <div class="menu-name">
                                Lokasi
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section mt-2" id="presence-section">
            <div class="todaypresence">
                <div class="row">
                    <div class="col-12">
                        <div class="card gradasigreen">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        @if ($presensihariini != null)
                                            @php
                                                $path = Storage::url('uploads/absensi/' . $presensihariini->foto_in);
                                            @endphp
                                            <img src="{{ url($path) }}" alt="" class="imaged w64">
                                        @else
                                            <ion-icon name="camera"></ion-icon>
                                        @endif
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitle">Masuk</h4>
                                        <span>{{ $presensihariini != null ? $presensihariini->jam_in : 'Belum Absen' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="rekappresensi">
                <h3>Rekap Presensi Bulan {{ $namabulan[$bulanini] }} Tahun {{ $tahunini }}</h3>
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 12px 12px !important; line-height:1rem">
                                <span class="badge bg-danger"
                                    style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlhadir }}</span>
                                <ion-icon name="accessibility-outline" style="font-size: 1.6rem;"
                                    class="text-primary mb-1"></ion-icon>
                                <br>
                                <span style="font-size: 1rem; font-weight:500">Hadir</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-body text-center" style="padding: 12px 12px !important; line-height:1rem">
                                <span class="badge bg-danger"
                                    style="position: absolute; top:3px; right:10px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlterlambat }}</span>
                                <ion-icon name="alarm-outline" style="font-size: 1.6rem;"
                                    class="text-danger mb-1"></ion-icon>
                                <br>
                                <span style="font-size: 1rem; font-weight:500">Telat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="presencetab mt-2">
                <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                    <ul class="nav nav-tabs style1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                Bulan Ini
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                Leaderboard
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-2" style="margin-bottom:100px;">
                    <div class="tab-pane fade show active" id="home" role="tabpanel">
                        <!--
                                                                                                                        <ul class="listview image-listview">
                                                                                                                            @foreach ($historibulanini as $d)
    @php
        $path = Storage::url('uploads/absensi' . $d->foto_in);
    @endphp
                                                                                                                                <li>
                                                                                                                                    <div class="item">
                                                                                                                                        <div class="icon-box bg-primary">
                                                                                                                                            <ion-icon name="image"></ion-icon>
                                                                                                                                        </div>
                                                                                                                                        <div class="in">
                                                                                                                                            <div>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</div>
                                                                                                                                                <span class="badge badge-success">{{ $d->jam_in }}</span>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                </li>
    @endforeach
                                                                                                                                                                                                                                                                    </ul>
                                                                                                                                    -->
                        <style>
                            .historicontent {
                                display: flex;
                                margin-top: 10px;
                            }

                            .datapresensi {
                                margin-left: 10px;
                            }
                        </style>
                        @foreach ($historibulanini as $d)
                            <div class="card mb-1">
                                <div class="card-body">
                                    <div class="historicontent">
                                        <div class="iconpresensi">
                                            <ion-icon name="image" style="font-size: 48px;"
                                                class="text-success"></ion-icon>
                                        </div>
                                        <div class="datapresensi">
                                            <h3 style="line-height: 3px">{{ $d->nama_jam_kerja }}</h3>
                                            <h4 style="margin: 0px !important">
                                                {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}
                                            </h4>
                                            <span>
                                                {!! $d->jam_in != null ? date('H:i', strtotime($d->jam_in)) : '<span class="text-danger">Belum Absen</span>' !!}
                                            </span>
                                            <br>
                                            <div id="keterangan" class="mt-0.5">
                                                @php
                                                    // Jam Ketika Absen
                                                    $jam_in = date('H:i', strtotime($d->jam_in));

                                                    // Jam Masuk Sesuai Jadwal
                                                    $jam_masuk = date('H:i', strtotime($d->jam_masuk));

                                                    $jadwal_jam_masuk = $d->tgl_presensi . ' ' . $d->jam_masuk;
                                                    $jam_presensi = $d->tgl_presensi . ' ' . $d->jam_in;
                                                @endphp
                                                @if ($jam_in > $jam_masuk)
                                                    @php
                                                        $jmlterlambat = hitungjamterlambat(
                                                            $jadwal_jam_masuk,
                                                            $jam_presensi,
                                                        );
                                                    @endphp
                                                    <span class="danger">Terlambat {{ $jmlterlambat }}</span>
                                                @else
                                                    <span style="color:green">On Time</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        <ul class="listview image-listview">
                            @foreach ($leaderboard as $d)
                                <li>
                                    <div class="item">
                                        <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                        <div class="in">
                                            <div>
                                                <d>{{ $d->nama_lengkap }}</d><br>
                                                <small class="text-muted">{{ $d->jabatan }}</small>
                                            </div>
                                            <span
                                                class="badge {{ $d->jam_in < '13:00' ? 'badge-success' : 'badge-danger' }}">
                                                {{ $d->jam_in }}
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    @endsection
