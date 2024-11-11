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

        return $this->_collect($query, $start, $end, 'H:00', '+1 hour');
    }
    public function get_traffic_this_week(){
        $end = new DateTime('23:59:59');
        $start = new DateTime(date('Y-m-d 00:00:00', strtotime('-1 week', $end->getTimestamp())));
        
        $query = DB::table($this->table)
            ->selectRaw("DATE(tanggal) AS tanggal, COUNT(*) AS jumlah")
            ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
            ->groupByRaw("DATE(tanggal)")
            ->get();
        
        return $this->_collect($query, $start, $end, 'd M', '+1 day');
    }
    public function get_traffic_this_month(){
        $end = new DateTime('23:59:59');
        $start = new DateTime(date('Y-m-d 00:00:00', strtotime('-1 month', $end->getTimestamp())));
        
        $query = DB::table($this->table)
            ->selectRaw("DATE(tanggal) AS tanggal, COUNT(*) AS jumlah")
            ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
            ->groupByRaw("DATE(tanggal)")
            ->get();

        return $this->_collect($query, $start, $end, 'd M', '+1 day');
    }
    public function get_traffic_this_year(){
        $end = new DateTime('23:59:59');
        $start = new DateTime(date('Y-m-d 00:00:00', strtotime('-1 year', $end->getTimestamp())));
        
        $query = DB::table($this->table)
            ->selectRaw("DATE_FORMAT(`tanggal`, '%Y-%m-01') AS tanggal, COUNT(*) AS jumlah")
            ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
            ->groupByRaw("DATE_FORMAT(`tanggal`, '%Y-%m')")
            ->get();

        return $this->_collect($query, $start, $end, 'M Y', '+1 month');
    }

    public function get_kendaraan_today(){
        $start = new DateTime('00:00:00');
        $end = new DateTime('23:59:59');

        return $this->_get_kendaraan_in_period($start, $end);
    }
    public function get_kendaraan_this_week(){
        $end = new DateTime('23:59:59');
        $start = new DateTime(date('Y-m-d 00:00:00', strtotime('-1 week', $end->getTimestamp())));
        
        return $this->_get_kendaraan_in_period($start, $end);
    }
    public function get_kendaraan_this_month(){
        $end = new DateTime('23:59:59');
        $start = new DateTime(date('Y-m-d 00:00:00', strtotime('-1 month', $end->getTimestamp())));
        
        return $this->_get_kendaraan_in_period($start, $end);
    }
    public function get_kendaraan_this_year(){
        $end = new DateTime('23:59:59');
        $start = new DateTime(date('Y-m-d 00:00:00', strtotime('-1 year', $end->getTimestamp())));
        
        return $this->_get_kendaraan_in_period($start, $end);
    }

    private function _collect($query, $start, $end, $format, $increment){
        $data = [];
        foreach ($query as $d) {
            $jam = date($format, strtotime($d->tanggal));
            $data[$jam] = $d->jumlah;
        }

        $result = [];
        $current_date = clone $start;
        while ($current_date <= $end) {
            $tanggal = $current_date->format($format);
            $result[$tanggal] = $data[$tanggal] ?? 0;  // Jika tanggal tidak ada di $data, set ke 0
            $current_date->modify($increment);
        }

        return $result;
    }
    private function _get_kendaraan_in_period($start, $end){
        $query = DB::table('jenis_kendaraan', 'jk')
            ->selectRaw("jk.jenis, COALESCE(COUNT(t.id), 0) AS jumlah")
            ->leftJoin(
                DB::raw($this->table." t"), 
                'jk.id', '=', 
                DB::raw("t.id_jenis AND t.tanggal BETWEEN '"
                    .$start->format('Y-m-d H:i:s')."' AND '"
                    .$end->format('Y-m-d H:i:s')."'"
                )
            )
            ->groupByRaw("jk.id")
            ->get();
        
        $jumlah = 0;
        foreach ($query as $d) {
            $jumlah += $d->jumlah;
        }

        $result = [];
        foreach ($query as $d) {
            if($jumlah == 0){ $presentase = 0; }
            else{ $presentase = $d->jumlah / $jumlah * 100; }

            $result[$d->jenis] = round($presentase, 2);
        }

        return $result;
    }
}
