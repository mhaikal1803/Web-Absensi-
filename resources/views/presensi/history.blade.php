@extends('layouts.presensi')
@section('header')
    <--- App Header -->
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="/dashboard" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">History Presensi</div>
            <div class="right"></div>
        </div>
        <-- * App Header -->
        @endsection

        @section('content')
            <div class="row">
                <div class="col">
                    <div class="row" style="margin-top:70px">
                        <div class="col-8">
                            <div class="form-group">
                                <select name="bulan" id="bulan" class="form-control selectmaterialize">
                                    <option value="">Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option {{ Request('bulan') == $i ? 'selected' : '' }} value="{{ $i }}">
                                            {{ $namabulan[$i] }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <select name="tahun" id="tahun" class="form-control selectmaterialize">
                                    <option value="">Tahun</option>
                                    @php
                                        $tahun_awal = 2025;
                                        $tahun_sekarang = date('Y');
                                        for ($t = $tahun_awal; $t <= $tahun_sekarang; $t++) {
                                            if (Request('tahun') == $t) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            }
                                            echo "<option value='$t' $selected>$t</option>";
                                        }
                                    @endphp
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-primary w-100" id="btnsearch">Search</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2" style="position: fixed; width:100%; margin:auto; overflow-y:scroll; height: 430px">
                <div class="col" id="showhistory"></div>
            </div>
        @endsection

        @push('myscript')
            <script>
                $(function() {
                    $("#btnsearch").click(function(e) {
                        var bulan = $("#bulan").val();
                        var tahun = $("#tahun").val();
                        $.ajax({
                            type: 'POST',
                            url: '/gethistory',
                            data: {
                                _token: "{{ csrf_token() }}",
                                bulan: bulan,
                                tahun: tahun
                            },
                            cache: false,
                            success: function(respond) {
                                $("#showhistory").html(respond);
                            }
                        });
                    });
                });
            </script>
        @endpush
