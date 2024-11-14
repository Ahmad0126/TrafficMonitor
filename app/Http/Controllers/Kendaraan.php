<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan as KendaraanModel;
use Illuminate\Http\Request;

class Kendaraan extends Controller
{
    public function index(){
        $data['title'] = 'Daftar Kendaraan';
        $data['kendaraan'] = KendaraanModel::all();
        return view('kendaraan', $data);
    }
    public function tambah_kendaraan(Request $req){
        $req->validate([
            'jenis' => 'required|unique:jenis_kendaraan,jenis'
        ]);

        $kendaraan = new KendaraanModel();
        $kendaraan->jenis = $req->jenis;
        $kendaraan->save();

        return redirect(route('kendaraan'))->with('alert', 'Berhasil menambah kendaraan');
    }
    public function edit_kendaraan(Request $req){
        $req->validate([
            'jenis' => 'required|unique:jenis_kendaraan,jenis,'.$req->id.',id',
            'id' => 'required:jenis_kendaraan,id'
        ]);

        $kendaraan = KendaraanModel::find($req->id);
        $kendaraan->jenis = $req->jenis;
        $kendaraan->save();

        return redirect(route('kendaraan'))->with('alert', 'Berhasil mengedit kendaraan');
    }
}
