<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')
            ->get();
        return view('karyawan.index', compact('karyawan'));
    }

    public function store(Request $request)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $password = Hash::make('12345');
        if ($request->hasFile('foto')) {
            try {
                $upload = Cloudinary::uploadApi()->upload($request->file('foto')->getRealPath(), [
                    'folder' => 'presensigps/karyawan',
                    'public_id' => $nik,
                    'overwrite' => true,
                    'resource_type' => 'image',
                ]);

                $foto = $upload['secure_url'];
            } catch (\Exception $e) {
                return Redirect::back()->with(['warning' => 'Upload foto ke Cloudinary gagal']);
            }
        } else {
            $foto = null;
        }

        try {
            $data = [
                'nik' => $nik,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'foto' => $foto,
                'password' => $password
            ];
            $simpan = DB::table('karyawan')->insert($data);
            if ($simpan) {
                return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
            }
        } catch (\Exception $e) {
            if ($e->getCode() == '23000') {
                return Redirect::back()->with(['warning' => 'NIK Sudah Digunakan']);
            }
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit(Request $request)
    {
        $nik = $request->nik;
        $karyawan = DB::table('karyawan')->where('nik', $nik)->first();
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update($nik, Request $request)
    {
        $nik = $request->nik;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $password = Hash::make('12345');
        $old_foto = $request->old_foto;
        if ($request->hasFile('foto')) {
            try {
                $upload = Cloudinary::uploadApi()->upload($request->file('foto')->getRealPath(), [
                    'folder' => 'presensigps/karyawan',
                    'public_id' => $nik,
                    'overwrite' => true,
                    'resource_type' => 'image',
                ]);

                $foto = $upload['secure_url'];
            } catch (\Exception $e) {
                return Redirect::back()->with(['warning' => 'Upload foto ke Cloudinary gagal']);
            }
        } else {
            $foto = $old_foto;
        }

        try {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'foto' => $foto,
                'password' => $password
            ];
            $update = DB::table('karyawan')->where('nik', $nik)->update($data);
            if ($update) {
                return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
            }
        } catch (\Exception $e) {
            //dd($e);
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function delete($nik)
    {
        $delete = DB::table('karyawan')->where('nik', $nik)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Dihapus']);
        }
    }

    public function resetpassword($nik)
    {
        $nik = Crypt::decrypt($nik);
        $password = Hash::make('12345');
        $update = DB::table('karyawan')->where('nik', $nik)->update(['password' => $password]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Password Berhasil Direset']);
        } else {
            return Redirect::back()->with(['warning' => 'Password Gagal Direset']);
        }
    }
}
