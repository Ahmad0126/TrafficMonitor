<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Jalan as JalanModel;
use App\Http\Resources\Jalan as JalanResource;

class Jalan extends Controller
{
    public function index(){
        $data['title'] = 'Daftar Ruas Jalan';
        $data['jalan'] = JalanModel::all();
        return view('jalan', $data);
    }
    public function show(){
        $data['title'] = 'Daftar Ruas Jalan';
        $data['jalan'] = new JalanResource(JalanModel::all());
        $data['url_tambah'] = route('tambah_jalan');
        $data['url_edit'] = route('edit_jalan');
        return Inertia::render('Jalan', $data);
    }
    public function tambah_jalan(Request $req){
        $req->validate([
            'ruas' => 'required|unique:ruas_jalan,ruas'
        ]);

        $jalan = new JalanModel();
        $jalan->ruas = $req->ruas;
        $jalan->save();

        return redirect()->back()->with('alert', 'Berhasil menambah jalan');
    }
    public function edit_jalan(Request $req){
        $req->validate([
            'ruas' => 'required|unique:ruas_jalan,ruas,'.$req->id.',id',
            'id' => 'required:ruas_jalan,id'
        ]);

        $jalan = JalanModel::find($req->id);
        $jalan->ruas = $req->ruas;
        $jalan->save();

        return redirect()->back()->with('alert', 'Berhasil mengedit jalan');
    }
}
