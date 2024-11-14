<?php

namespace App\Http\Controllers;

use App\Models\Jalan as JalanModel;
use Illuminate\Http\Request;

class Jalan extends Controller
{
    public function index(){
        $data['title'] = 'Daftar Ruas Jalan';
        $data['jalan'] = JalanModel::all();
        return view('jalan', $data);
    }
    public function tambah_jalan(Request $req){
        $req->validate([
            'ruas' => 'required|unique:ruas_jalan,ruas'
        ]);

        $jalan = new JalanModel();
        $jalan->ruas = $req->ruas;
        $jalan->save();

        return redirect(route('jalan'))->with('alert', 'Berhasil menambah jalan');
    }
    public function edit_jalan(Request $req){
        $req->validate([
            'ruas' => 'required|unique:ruas_jalan,ruas,'.$req->id.',id',
            'id' => 'required:ruas_jalan,id'
        ]);

        $jalan = JalanModel::find($req->id);
        $jalan->ruas = $req->ruas;
        $jalan->save();

        return redirect(route('jalan'))->with('alert', 'Berhasil mengedit jalan');
    }
}
