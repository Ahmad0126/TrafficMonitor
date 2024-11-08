<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Traffic extends Model
{
    use HasFactory;
    protected $table = 'traffic';

    public function kendaraan():BelongsTo{
        return $this->belongsTo(Kendaraan::class, 'id_jenis');
    }
    public function jalan():BelongsTo{
        return $this->belongsTo(Jalan::class, 'id_ruas');
    }
    public function get_traffic_today(){
        $start = new DateTime('00:00:00');
        $end = new DateTime('23:59:59');
        
        $query = DB::table($this->table)
            ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m-%d %H:00:00') AS tanggal, COUNT(*) AS jumlah")
            ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
            ->groupByRaw("DATE_FORMAT(tanggal, '%Y-%m-%d %H')")
            ->get();

        $data = [];
        foreach ($query as $d) {
            $jam = date('H:00', strtotime($d->tanggal));
            $data[$jam] = $d->jumlah;
        }

        $result = [];
        $current_date = clone $start;
        while ($current_date <= $end) {
            $tanggal = $current_date->format('H:00');
            $result[$tanggal] = $data[$tanggal] ?? 0;  // Jika tanggal tidak ada di $data, set ke 0
            $current_date->modify('+1 hour');
        }

        return $result;
    }
    public function get_traffic_this_week(){
        $end = new DateTime('00:00:00');
        $start = new DateTime(date('Y-m-d 23:59:59', strtotime('-1 week', $end->getTimestamp())));
        
        $query = DB::table($this->table)
            ->selectRaw("DATE(tanggal) AS tanggal, COUNT(*) AS jumlah")
            ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
            ->groupByRaw("DATE(tanggal)")
            ->get();
        
        $data = [];
        foreach ($query as $d) {
            $day = date('d M', strtotime($d->tanggal));
            $data[$day] = $d->jumlah;
        }

        $result = [];
        $current_date = clone $start;
        while ($current_date <= $end) {
            $tanggal = $current_date->format('d M');
            $result[$tanggal] = $data[$tanggal] ?? 0;  // Jika tanggal tidak ada di $data, set ke 0
            $current_date->modify('+1 day');
        }

        return $result;
    }
    public function get_traffic_this_month(){
        $end = new DateTime('00:00:00');
        $start = new DateTime(date('Y-m-d 23:59:59', strtotime('-1 month', $end->getTimestamp())));
        
        $query = DB::table($this->table)
            ->selectRaw("DATE(tanggal) AS tanggal, COUNT(*) AS jumlah")
            ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
            ->groupByRaw("DATE(tanggal)")
            ->get();

        $data = [];
        foreach ($query as $d) {
            $day = date('d M', strtotime($d->tanggal));
            $data[$day] = $d->jumlah;
        }

        $result = [];
        $current_date = clone $start;
        while ($current_date <= $end) {
            $tanggal = $current_date->format('d M');
            $result[$tanggal] = $data[$tanggal] ?? 0;  // Jika tanggal tidak ada di $data, set ke 0
            $current_date->modify('+1 day');
        }

        return $result;
    }
    public function get_traffic_this_year(){
        $end = new DateTime('00:00:00');
        $start = new DateTime(date('Y-m-d 23:59:59', strtotime('-1 year', $end->getTimestamp())));
        
        $query = DB::table($this->table)
            ->selectRaw("DATE_FORMAT(`tanggal`, '%Y-%m-01') AS tanggal, COUNT(*) AS jumlah")
            ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
            ->groupByRaw("DATE_FORMAT(`tanggal`, '%Y-%m')")
            ->get();

        $data = [];
        foreach ($query as $d) {
            $bulan = date('M Y', strtotime($d->tanggal));
            $data[$bulan] = $d->jumlah;
        }

        $result = [];
        $current_date = clone $start;
        while ($current_date <= $end) {
            $tanggal = $current_date->format('M Y');
            $result[$tanggal] = $data[$tanggal] ?? 0;  // Jika tanggal tidak ada di $data, set ke 0
            $current_date->modify('+1 month');
        }

        return $result;
    }
}
