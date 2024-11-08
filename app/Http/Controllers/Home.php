<?php

namespace App\Http\Controllers;

use App\Models\Traffic;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;

class Home extends Controller
{
    public function index(){
        return view('tes_grafik', ['title' => 'Home']);
    }
    public function tes_grafik(){
        $traffic = new Traffic();
        dd(
            $traffic->get_traffic_this_year(), 
            $traffic->get_traffic_this_month(), 
            $traffic->get_traffic_this_week(), 
            $traffic->get_traffic_today(),
        );
    }
}
