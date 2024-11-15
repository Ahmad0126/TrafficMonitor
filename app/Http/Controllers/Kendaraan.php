<?php

namespace App\Http\Controllers;

use App\Http\Resources\Kendaraan as KendaraanResource;
use App\Models\Kendaraan as KendaraanModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class Kendaraan extends Controller
{
    public function index(){
        $data['title'] = 'Daftar Kendaraan';
        $data['kendaraan'] = KendaraanModel::all();
        return view('kendaraan', $data);
    }
    public function show(){
        $data['title'] = 'Daftar Kendaraan';
        $data['kendaraan'] = new KendaraanResource(KendaraanModel::all());
        $data['url_tambah'] = route('tambah_kendaraan');
        $data['url_edit'] = route('edit_kendaraan');
        return Inertia::render('Kendaraan', $data);
    }
    public function tambah_kendaraan(Request $req){
        $req->validate([
            'jenis' => 'required|unique:jenis_kendaraan,jenis'
        ]);

        $kendaraan = new KendaraanModel();
        $kendaraan->jenis = $req->jenis;
        $kendaraan->save();

        return redirect()->back()->with('alert', 'Berhasil menambah kendaraan');
    }
    public function edit_kendaraan(Request $req){
        $req->validate([
            'jenis' => 'required|unique:jenis_kendaraan,jenis,'.$req->id.',id',
            'id' => 'required:jenis_kendaraan,id'
        ]);

        $kendaraan = KendaraanModel::find($req->id);
        $kendaraan->jenis = $req->jenis;
        $kendaraan->save();

        return redirect()->back()->with('alert', 'Berhasil mengedit kendaraan');
    }
}
