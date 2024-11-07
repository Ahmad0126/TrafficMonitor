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
        $kendaraan = new KendaraanModel();
        $kendaraan->jenis = $req->jenis;
        $kendaraan->save();

        return redirect(route('kendaraan'));
    }
}
