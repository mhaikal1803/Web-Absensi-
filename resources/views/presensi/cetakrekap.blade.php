<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
        }

        #title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }

        .tabledatakaryawan {
            margin-top: 40px;
        }

        .tabledatakaryawan td {
            padding: 5px;
        }

        .tablepresensi {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tablepresensi tr th {
            border: 1px solid #000000;
            padding: 8px;
            background: #979595;
            font-size: 9px;
        }

        .tablepresensi tr td {
            border: 1px solid #000000;
            padding: 5px;
            font-size: 9px;
        }

        .foto {
            width: 40px;
            height: 30px;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4 landscape">
    <?php
    function selisih($jam_masuk, $jam_keluar)
    {
        [$h, $m, $s] = explode(':', $jam_masuk);
        $dtAwal = mktime($h, $m, $s, '1', '1', '1');
        [$h, $m, $s] = explode(':', $jam_keluar);
        $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
        $dtSelisih = $dtAkhir - $dtAwal;
        $totalmenit = $dtSelisih / 60;
        $jam = explode('.', $totalmenit / 60);
        $sisamenit = $totalmenit / 60 - $jam[0];
        $sisamenit2 = $sisamenit * 60;
        $jml_jam = $jam[0];
        return $jml_jam . ':' . round($sisamenit2);
    }
    ?>
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <table style="width: 100%">
            <tr>
                <td style="width: 30px">
                    <img src="{{ asset('assets/img/logo_cetak.png') }}" width="70" height="70" alt="">
                </td>
                <td>
                    <span id="title">
                        Rekap Presensi Karyawan<br>
                        Periode {{ $namabulan[$bulan] }} {{ $tahun }}<br>
                        Cafe Magi<br>
                    </span>
                    <span><i>Jl. Amal Luhur No.75, Dwi Kora, Kec. Medan Helvetia, Kota Medan, Sumatera Utara
                            20123</i></span>
                </td>
            </tr>
        </table>

        <table class="tablepresensi">
            <tr>
                <th rowspan="2">Nik</th>
                <th rowspan="2">Nama Karyawan</th>
                <th colspan="{{ $jmlhari }}">Bulan{{ $namabulan[$bulan] }} {{ $tahun }}</th>
                <th rowspan="2">H</th>
                <th rowspan="2">T</th>
                <th rowspan="2">A</th>
            </tr>
            <tr>
                @foreach ($rangetanggal as $d)
                    @if ($d != null)
                        <th>{{ date('d', strtotime($d)) }}</th>
                    @endif
                @endforeach
            </tr>
            @foreach ($rekap as $r)
                <tr>
                    <td>{{ $r->nik }}</td>
                    <td>{{ $r->nama_lengkap }}</td>

                    <?php
                    $jml_hadir = 0;
                    $jml_telat = 0;
                    $jml_alpa = 0;
                    for ($i = 1; $i <= $jmlhari; $i++) {
                        $tgl = 'tgl_' . $i;
                        $datapresnsi = explode('|', $r->$tgl);
                        if ($r->$tgl != NULL) {
                            $status = $datapresnsi[1];
                        } else {
                            $status = '';
                        }
                        if ($status == 'hadir') {
                            $jml_hadir += 1;
                        }

                        if ($status == 'telat') {
                            $jml_telat += 1;
                        }

                        if (empty($status)) {
                            $jml_alpa += 1;
                        }
                    ?>
                    <td>
                        @if ($r->$tgl != null)
                            @if ($status == 'hadir')
                                H
                            @elseif ($status == 'telat')
                                T
                            @endif
                        @endif
                    </td>
                    <?php
                        }
                    ?>
                    <td>{{ !empty($jml_hadir) ? $jml_hadir : '' }}</td>
                    <td>{{ !empty($jml_telat) ? $jml_telat : '' }}</td>
                    <td>{{ !empty($jml_alpa) ? $jml_alpa : '' }}</td>

                </tr>
            @endforeach
        </table>

        <table width="100%" style="margin-top: 100px">
            <tr>
                <td></td>
                <td style="text-align: center">Medan, {{ date('d-m-Y') }}</td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align:bottom" height="100px">
                    <u>Muhammad Haikal</u><br>
                    <i><b>Manager</b></i>
                </td>
                <td style="text-align: center; vertical-align:bottom">
                    <u>Kade</u><br>
                    <i><b>Owner</b></i>
                </td>
            </tr>

        </table>
    </section>

</body>

</html>
