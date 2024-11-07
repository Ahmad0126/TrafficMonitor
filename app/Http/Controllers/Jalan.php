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
        $jalan = new JalanModel();
        $jalan->ruas = $req->ruas;
        $jalan->save();

        return redirect(route('jalan'));
    }
}
