<?php
function hitungjamterlambat($jadwal_jam_masuk, $jam_presensi)
{
    $j1 = strtotime($jadwal_jam_masuk);
    $j2 = strtotime($jam_presensi);

    $diffterlambat = $j2 - $j1;

    $jamterlambat = floor($diffterlambat / (60 * 60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat * 60 * 60)) / 60);

    $jterlambat = $jamterlambat <= 9 ? '0' . $jamterlambat : $jamterlambat;
    $mterlambat = $menitterlambat <= 9 ? '0' . $menitterlambat : $menitterlambat;

    $terlambat = $jterlambat . ':' . $mterlambat;
    return $terlambat;
}
