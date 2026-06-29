@extends('layouts.admin.tabler')
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        Data Karyawan
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    @if (Session::get('success'))
                                        <div class="alert alert-success">
                                            {{ Session::get('success') }}
                                        </div>
                                    @endif

                                    @if (Session::get('warning'))
                                        <div class="alert alert-warning">
                                            {{ Session::get('warning') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <a href="#" class="btn btn-primary" id="btnTambahkaryawan">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="currentColor"
                                            class="icon icon-tabler icons-tabler-filled icon-tabler-plus">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 0 1 0 2h-6v6a1 1 0 0 1 -2 0v-6h-6a1 1 0 0 1 0 -2h6v-6a1 1 0 0 1 1 -1" />
                                        </svg>
                                        Tambah Data
                                    </a>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nik</th>
                                                <th>Nama</th>
                                                <th>Jabatan</th>
                                                <th>No Hp</th>
                                                <th>Foto</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($karyawan as $d)
                                                @php
                                                    $path = \Illuminate\Support\Str::startsWith($d->foto, 'http')
                                                        ? $d->foto
                                                        : url(Storage::url('uploads/karyawan/' . $d->foto));
                                                @endphp
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $d->nik }}</td>
                                                    <td>{{ $d->nama_lengkap }}</td>
                                                    <td>{{ $d->jabatan }}</td>
                                                    <td>{{ $d->no_hp }}</td>
                                                    <td>
                                                        @if (empty($d->foto))
                                                            <img src="{{ asset('assets/img/noprofile.jpg') }}"
                                                                class='avatar'alt="">
                                                        @else
                                                            <img src="{{ $path }}" class="avatar" alt="">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <div>
                                                                <a href="#" class="edit btn btn-info btn-sm"
                                                                    nik="{{ $d->nik }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-edit">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                                        <path
                                                                            d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415" />
                                                                        <path d="M16 5l3 3" />
                                                                    </svg>
                                                                </a>
                                                                <a href="/konfigurasi/{{ $d->nik }}/setjamkerja"
                                                                    class="btn btn-success btn-sm">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-settings">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065" />
                                                                        <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                                                    </svg>
                                                                </a>
                                                                <a href="/karyawan/{{ Crypt::encrypt($d->nik) }}/resetpassword"
                                                                    class="btn btn-sm btn-warning">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                        height="24" viewBox="0 0 24 24" fill="none"
                                                                        stroke="currentColor" stroke-width="2"
                                                                        stroke-linecap="round" stroke-linejoin="round"
                                                                        class="icon icon-tabler icons-tabler-outline icon-tabler-key">
                                                                        <path stroke="none" d="M0 0h24v24H0z"
                                                                            fill="none" />
                                                                        <path
                                                                            d="M16.555 3.843l3.602 3.602a2.877 2.877 0 0 1 0 4.069l-2.643 2.643a2.877 2.877 0 0 1 -4.069 0l-.301 -.301l-6.558 6.558a2 2 0 0 1 -1.239 .578l-.175 .008h-1.172a1 1 0 0 1 -.993 -.883l-.007 -.117v-1.172a2 2 0 0 1 .467 -1.284l.119 -.13l.414 -.414h2v-2h2v-2l2.144 -2.144l-.301 -.301a2.877 2.877 0 0 1 0 -4.069l2.643 -2.643a2.877 2.877 0 0 1 4.069 0" />
                                                                        <path d="M15 9h.01" />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                            <div>
                                                                <form action="/karyawan/{{ $d->nik }}/delete"
                                                                    method="POST" style="margin-left: 5px">
                                                                    @csrf
                                                                    <a class="btn btn-danger btn-sm delete-confirm">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            width="24" height="24"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                                            <path stroke="none" d="M0 0h24v24H0z"
                                                                                fill="none" />
                                                                            <path d="M4 7l16 0" />
                                                                            <path d="M10 11l0 6" />
                                                                            <path d="M14 11l0 6" />
                                                                            <path
                                                                                d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                            <path
                                                                                d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                                        </svg>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal modal-blur fade" id="modal-inputkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Data Karyawan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="/karyawan/store" method="POST" id="frmKaryawan"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-icon mb-3">
                                            <span class="input-icon-addon">
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/user -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-id">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M3 7a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3l0 -10" />
                                                    <path d="M7 10a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                    <path d="M15 8l2 0" />
                                                    <path d="M15 12l2 0" />
                                                    <path d="M7 16l10 0" />
                                                </svg>
                                            </span>
                                            <input type="text" maxlength="5" value="" id="nik"
                                                class="form-control" placeholder="Nik" name="nik">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-icon mb-3">
                                            <span class="input-icon-addon">
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/user -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-1">
                                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                                </svg>
                                            </span>
                                            <input type="text" value="" id="nama_lengkap" class="form-control"
                                                placeholder="Nama Lengkap" name="nama_lengkap">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-icon mb-3">
                                            <span class="input-icon-addon">
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/user -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-briefcase-2">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M3 9a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9" />
                                                    <path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" />
                                                </svg>
                                            </span>
                                            <input type="text" value="" id="jabatan" class="form-control"
                                                placeholder="Jabatan" name="jabatan">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-icon mb-3">
                                            <span class="input-icon-addon">
                                                <!-- Download SVG icon from http://tabler.io/icons/icon/user -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-device-mobile">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M6 5a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2v-14" />
                                                    <path d="M11 4h2" />
                                                    <path d="M12 17v.01" />
                                                </svg>
                                            </span>
                                            <input type="text" value="" id="no_hp" class="form-control"
                                                placeholder="No Hp" name="no_hp">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <input type="file" name="foto" class="form-control">
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button class="btn btn-primary w-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="currentColor"
                                                    class="icon icon-tabler icons-tabler-filled icon-tabler-send">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M21.864 3.549l-6.454 17.868a1.55 1.55 0 0 1 -1.41 .903a1.54 1.54 0 0 1 -1.394 -.874l-2.88 -5.759zm-1.414 -1.414l-12.139 12.138l-5.728 -2.864a1.55 1.55 0 0 1 -.903 -1.409c0 -.606 .353 -1.157 .981 -1.44z" />
                                                </svg>
                                                Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Edit --}}
            <div class="modal modal-blur fade" id="modal-edittkaryawan" tabindex="-1" role="dialog"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Karyawan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="loadeditform">

                        </div>

                    </div>
                </div>
            </div>
        @endsection

        @push('myscript')
            <script>
                $(function() {
                    $("#nik").mask("00000");
                    $("#no_hp").mask("0000000000000");
                    $("#btnTambahkaryawan").click(function() {
                        $("#modal-inputkaryawan").modal("show");
                    });

                    $(".edit").click(function() {
                        var nik = $(this).attr("nik");
                        $.ajax({
                            type: 'POST',
                            url: '/karyawan/edit',
                            cache: false,
                            data: {
                                _token: "{{ csrf_token() }}",
                                nik: nik
                            },
                            success: function(respond) {
                                $('#loadeditform').html(respond);
                            }
                        });
                        $("#modal-edittkaryawan").modal("show");
                    });

                    $(".delete-confirm").click(function(e) {
                        var form = $(this).closest('form');
                        e.preventDefault();
                        Swal.fire({
                            title: "Anda Yakin ?",
                            text: "Jika Iya, Data Akan Di Hapus",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Ya, Hapus"
                        }).then((result) => {
                            if (result.isConfirmed)
                                form.submit();
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your file has been deleted.",
                                icon: "success"
                            });
                        });
                    });

                    $('#frmKaryawan').submit(function() {
                        var nik = $('#nik').val();
                        var nama_lengkap = $('#nama_lengkap').val();
                        var jabatan = $('#jabatan').val();
                        var no_hp = $('#no_hp').val();
                        if (nik == "") {
                            //alert('Nik Harus Diisi');
                            Swal.fire({
                                title: 'Warning!',
                                text: 'Nik Harus Diisi !',
                                icon: 'warning',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                $("#nik").focus();
                            });
                            return false;
                        } else if (nama_lengkap == "") {
                            Swal.fire({
                                title: 'Warning!',
                                text: 'Nama Harus Diisi !',
                                icon: 'warning',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                $("#nama_lengkap").focus();
                            });
                            return false;
                        } else if (jabatan == "") {
                            Swal.fire({
                                title: 'Warning!',
                                text: 'Jabatan Harus Diisi !',
                                icon: 'warning',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                $("#jabatan").focus();
                            });
                            return false;
                        } else if (no_hp == "") {
                            Swal.fire({
                                title: 'Warning!',
                                text: 'No Hp Harus Diisi !',
                                icon: 'warning',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                $("#no_hp").focus();
                            });
                            return false;
                        }
                    });
                });
            </script>
        @endpush
