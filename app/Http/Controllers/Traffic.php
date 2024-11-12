<?php

namespace App\Http\Controllers;

use App\Models\Jalan;
use App\Models\Kendaraan;
use App\Models\Traffic as TrafficModel;
use Illuminate\Http\Request;

class Traffic extends Controller
{
    public function index(){
        $data['title'] = 'Daftar Traffic';
        $data['kendaraan'] = Kendaraan::all();
        $data['jalan'] = Jalan::all();
        $data['traffic'] = TrafficModel::paginate(25);
        $data['old'] = [
            'id_jenis' => null,
            'id_ruas' => null,
            'tanggal1' => null,
            'tanggal2' => null,
            'logic_speed' => null,
            'kecepatan' => null
        ];;
        return view('traffic', $data);
    }
    public function filter(Request $req){
        $data['title'] = 'Daftar Traffic';
        $data['kendaraan'] = Kendaraan::all();
        $data['jalan'] = Jalan::all();
        $traffic = TrafficModel::where([]);

        if($req->id_ruas != null){
            $req->validate(['id_ruas' => 'exists:ruas_jalan,id']);
            $traffic->where('id_ruas', $req->id_ruas);
        }
        if($req->id_jenis != null){
            $req->validate(['id_jenis' => 'exists:jenis_kendaraan,id']);
            $traffic->where('id_jenis', $req->id_jenis);
        }
        if($req->tanggal1 != null AND $req->tanggal2 != null){
            if(date('Y-m-d H:i:s', strtotime($req->tanggal2)) > date('Y-m-d H:i:s', strtotime($req->tanggal1)) AND date('Y-m-d H:i:s', strtotime($req->tanggal1)) < date('Y-m-d H:i:s')){
                $traffic->where('tanggal', '>', $req->tanggal1);
                $traffic->where('tanggal', '<', $req->tanggal2);
            }
        }
        if($req->kecepatan != null AND $req->logic_speed != null){
            switch ($req->logic_speed) {
                case 'lebih': $p = '>='; break;
                case 'kurang': $p = '<='; break;
                default: $p = '='; break;
            }
            $traffic->where('kecepatan', $p, $req->kecepatan);
        }

        $query = [
            'id_jenis' => $req->id_jenis,
            'id_ruas' => $req->id_ruas,
            'tanggal1' => $req->tanggal1,
            'tanggal2' => $req->tanggal2,
            'logic_speed' => $req->logic_speed,
            'kecepatan' => $req->kecepatan
        ];
        $data['traffic'] = $traffic->paginate(25)->appends($query);
        $data['old'] = $query;
        return view('traffic', $data);
    }
}
