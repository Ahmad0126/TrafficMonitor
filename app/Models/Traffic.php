<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
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

    public static function get_all(){
        return self::select(['traffic.id', 'traffic.tanggal', 'traffic.kecepatan', 'j.ruas', 'k.jenis'])
            ->join(DB::raw('jenis_kendaraan k'), 'traffic.id_jenis', '=', 'k.id')
            ->join(DB::raw('ruas_jalan j'), 'traffic.id_ruas', '=', 'j.id')
            ->orderByDesc('traffic.tanggal')->paginate(25);
    }

    private function _get_period($period, $end_date = null){
        $end = new DateTime($end_date.'23:59:59');
        switch ($period) {
            case 'today':
                $start = new DateTime($end_date.'00:00:00');
                break;
            case 'week':
                $start = new DateTime(date('Y-m-d 00:00:00', strtotime('-1 week', $end->getTimestamp())));
                break;
            case 'month':
                $start = new DateTime(date('Y-m-d 00:00:00', strtotime('-1 month', $end->getTimestamp())));
                break;
            default:
                $start = new DateTime(date('Y-m-d 00:00:00', strtotime('-1 year', $end->getTimestamp())));
                break;
        }
        return ['start' => $start, 'end' => $end];
    }

    public function get_title_traffic($period, $end_date){
        $date = $this->_get_period($period, $end_date);
        $start = $date['start'];
        $end = $date['end'];
        $title = 'Data Traffic Tanggal ';
        switch ($period) {
            case 'today':
                $title = $end->format('j F Y');
                break;
            case 'week':
                $title = $start->format('j F Y').' sampai '.$end->format('j F Y');
                break;
            case 'month':
                $title = $start->format('j F Y').' sampai '.$end->format('j F Y');
                break;
            default:
                $title = $start->format('j F Y').' sampai '.$end->format('j F Y');
                break;
        }
        return $title;
    }
    public function get_traffic_in_period($period, $end_date = null, $filter = ""){
        $date = $this->_get_period($period, $end_date);
        $start = $date['start'];
        $end = $date['end'];
        if($filter == ""){ $filter = 'true'; }

        switch ($period) {
            case 'today':
                $query = DB::table($this->table)
                    ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m-%d %H:00:00') AS tanggal, COUNT(*) AS jumlah")
                    ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
                    ->whereRaw($filter)
                    ->groupByRaw("DATE_FORMAT(tanggal, '%Y-%m-%d %H')")
                    ->get();
                $format = 'H:00';
                $increment = '+1 hour';
                break;
            case 'week':
                $query = DB::table($this->table)
                    ->selectRaw("DATE(tanggal) AS tanggal, COUNT(*) AS jumlah")
                    ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
                    ->whereRaw($filter)
                    ->groupByRaw("DATE(tanggal)")
                    ->get();
                $format = 'd M';
                $increment = '+1 day';
                break;
            case 'month':
                $query = DB::table($this->table)
                    ->selectRaw("DATE(tanggal) AS tanggal, COUNT(*) AS jumlah")
                    ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
                    ->whereRaw($filter)
                    ->groupByRaw("DATE(tanggal)")
                    ->get();
                $format = 'd M';
                $increment = '+1 day';
                break;
            default:
                $query = DB::table($this->table)
                    ->selectRaw("DATE_FORMAT(`tanggal`, '%Y-%m-01') AS tanggal, COUNT(*) AS jumlah")
                    ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
                    ->whereRaw($filter)
                    ->groupByRaw("DATE_FORMAT(`tanggal`, '%Y-%m')")
                    ->get();
                $format = 'M Y';
                $increment = '+1 month';
                break;
        }

        return $this->_collect($query, $start, $end, $format, $increment);
    }
    public function get_kendaraan_in_period($period, $end_date = null, $filter = ""){
        $date = $this->_get_period($period, $end_date);
        return $this->_get_kendaraan_in_period($date['start'], $date['end'], $filter);
    }
    public function get_kecepatan_in_period($period, $end_date = null, $filter = ""){
        $date = $this->_get_period($period, $end_date);
        return $this->_get_kecepatan_in_period($date['start'], $date['end'], $filter);
    }
    public function get_rata2_kecepatan($period, $end_date = null, $filter = ""){
        $date = $this->_get_period($period, $end_date);
        return $this->_get_rata2_kecepatan($date['start'], $date['end'], $filter);
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
    private function _get_kendaraan_in_period($start, $end, $filter = ""){
        if($filter == ""){ $filter = 'true'; }
        $query = DB::table('jenis_kendaraan', 'jk')
            ->selectRaw("jk.jenis, COALESCE(COUNT(t.id), 0) AS jumlah")
            ->leftJoin(
                DB::raw($this->table." t"), 
                'jk.id', '=', 
                DB::raw("t.id_jenis AND t.tanggal BETWEEN '"
                    .$start->format('Y-m-d H:i:s')."' AND '"
                    .$end->format('Y-m-d H:i:s')."'"
                    .($filter != ""? ' AND '.$filter : '')
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
    private function _get_kecepatan_in_period($start, $end, $filter = ""){
        if($filter == ""){ $filter = 'true'; }
        $kriteria_kecepatan = [
            '<20' => 20,
            '>20' => 20,
            '>30' => 30,
            '>40' => 40,
            '>50' => 50,
            '>60' => 60,
            '>70' => 70,
            '>80' => 80,
        ];

        $query = DB::table($this->table)
            ->select("kecepatan")
            ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
            ->whereRaw($filter)
            ->get();

        $result = array_fill_keys(array_keys($kriteria_kecepatan), 0);
        foreach ($query as $d) {
            $n = 1;
            foreach ($kriteria_kecepatan as $kriteria => $batas) {
                if($n == count($kriteria_kecepatan)){
                    $kurang_dari = true;
                }else{
                    $kurang_dari = $d->kecepatan <= ($batas + 10);
                }

                if (($kriteria[0] === '<' && $d->kecepatan <= $batas) ||
                    ($kriteria[0] === '>' && $d->kecepatan > $batas && $kurang_dari)) {
                    $result[$kriteria]++;
                }
                $n++;
            }
        }

        return $result;
    }
    private function _get_rata2_kecepatan($start, $end, $filter = ""){
        if($filter == ""){ $filter = 'true'; }
        $query = DB::table($this->table)
            ->selectRaw("AVG(kecepatan) as speed")
            ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
            ->whereRaw($filter)
            ->limit(1)
            ->get()->first();

        return $query->speed;
    }
}
