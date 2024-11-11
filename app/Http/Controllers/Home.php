<?php

namespace App\Http\Controllers;

use App\Models\Traffic;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;

class Home extends Controller
{
    public function index(Request $req){
        $traffic = new Traffic();
        
        switch ($req->period) {
            case 'today':
                $data['volume'] = $traffic->get_traffic_today();
                $data['kendaraan'] = $traffic->get_kendaraan_today();
                break;
            case 'week':
                $data['volume'] = $traffic->get_traffic_this_week();
                $data['kendaraan'] = $traffic->get_kendaraan_this_week();
                break;
            case 'month':
                $data['volume'] = $traffic->get_traffic_this_month();
                $data['kendaraan'] = $traffic->get_kendaraan_this_month();
                break;
            default:
                $data['volume'] = $traffic->get_traffic_this_year();
                $data['kendaraan'] = $traffic->get_kendaraan_this_year();
                break;
        }
        
        $data['title'] = 'Home';
        $data['period'] = $req->period;
        return view('tes_grafik', $data);
    }
    public function tes_grafik(){
        $traffic = new Traffic();
        ddd(
            $traffic->get_kendaraan_this_year(), 
            $traffic->get_kendaraan_this_month(), 
            $traffic->get_kendaraan_this_week(), 
            $traffic->get_kendaraan_today(),
        );
    }
}
