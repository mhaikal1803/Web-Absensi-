<style>
    .historicontent {
        display: flex;
        margin-top: 10px;
    }

    .datapresensi {
        margin-left: 10px;
    }
</style>

@if ($history->isEmpty())
    <div class="text-center">
        <p>Tidak ada data presensi yang ditemukan.</p>
    </div>
@endif
@foreach ($history as $d)
    <div class="card mb-1">
        <div class="card-body">
            <div class="historicontent">
                <div class="iconpresensi">
                    <ion-icon name="image" style="font-size: 48px;" class="text-success"></ion-icon>
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
                                $jmlterlambat = hitungjamterlambat($jadwal_jam_masuk, $jam_presensi);
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
