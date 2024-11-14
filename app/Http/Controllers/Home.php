<?php

namespace App\Http\Controllers;

use App\Models\Traffic;
use Illuminate\Http\Request;

class Home extends Controller
{
    public function index(Request $req){
        $traffic = new Traffic();
        
        $data['volume'] = $traffic->get_traffic_in_period($req->period);
        $data['kendaraan'] = $traffic->get_kendaraan_in_period($req->period);
        $data['kecepatan'] = $traffic->get_kecepatan_in_period($req->period);
        $data['rata2'] = $traffic->get_rata2_kecepatan($req->period);
        $data['title'] = 'Home';
        $data['period'] = $req->period;
        return view('home', $data);
    }
    public function tes_grafik(){
        $traffic = new Traffic();
        ddd(
            $traffic->get_kecepatan_this_year(), 
            $traffic->get_kecepatan_this_month(), 
            $traffic->get_kecepatan_this_week(), 
            $traffic->get_kecepatan_today(),
        );
    }
}
