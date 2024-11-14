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
        $data['traffic'] = TrafficModel::orderBy('tanggal', 'DESC')->paginate(25);
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
        if($req->order != null){
            switch ($req->order) {
                case 'terlama':
                    $traffic->orderBy('tanggal', 'ASC');
                    break;
                case 'tercepat':
                    $traffic->orderBy('kecepatan', 'DESC');
                    break;
                case 'terlambat':
                    $traffic->orderBy('kecepatan', 'ASC');
                    break;
                default:
                    $traffic->orderBy('tanggal', 'DESC');
                    break;
            }
        }

        $query = [
            'id_jenis' => $req->id_jenis,
            'id_ruas' => $req->id_ruas,
            'tanggal1' => $req->tanggal1,
            'tanggal2' => $req->tanggal2,
            'logic_speed' => $req->logic_speed,
            'kecepatan' => $req->kecepatan,
            'order' => $req->order,
        ];
        $data['traffic'] = $traffic->paginate(25)->appends($query);
        $data['old'] = $query;
        return view('traffic', $data);
    }
    public function graph(Request $req){
        $data['jenis'] = Kendaraan::all();
        $data['jalan'] = Jalan::all();
        $traffic = new TrafficModel();
        $filter_traffic = TrafficModel::where([]);

        if($req->id_ruas != null){
            $req->validate(['id_ruas' => 'exists:ruas_jalan,id']);
            $filter_traffic->where('id_ruas', $req->id_ruas);
        }
        if($req->id_jenis != null){
            $req->validate(['id_jenis' => 'exists:jenis_kendaraan,id']);
            $filter_traffic->where('id_jenis', $req->id_jenis);
        }
        if($req->kecepatan != null AND $req->logic_speed != null){
            switch ($req->logic_speed) {
                case 'lebih': $p = '>='; break;
                case 'kurang': $p = '<='; break;
                default: $p = '='; break;
            }
            $filter_traffic->where('kecepatan', $p, $req->kecepatan);
        }
        
        $q_filter = (implode(" ", array_slice(explode(' ', $filter_traffic->toRawSql()), 5)));
        
        $data['volume'] = $traffic->get_traffic_in_period($req->period, $req->end_date, $q_filter);
        $data['kendaraan'] = $traffic->get_kendaraan_in_period($req->period, $req->end_date, $q_filter);
        $data['kecepatan'] = $traffic->get_kecepatan_in_period($req->period, $req->end_date, $q_filter);
        $data['rata2'] = $traffic->get_rata2_kecepatan($req->period, $req->end_date, $q_filter);
        $data['title'] = $traffic->get_title_traffic($req->period, $req->end_date, $q_filter);
        $data['old'] = [
            'period' => $req->period, 
            'end_date' => $req->end_date,
            'id_jenis' => $req->id_jenis,
            'id_ruas' => $req->id_ruas,
            'logic_speed' => $req->logic_speed,
            'kecepatan' => $req->kecepatan,
        ];
        return view('traffic_graph', $data);
    }
}
