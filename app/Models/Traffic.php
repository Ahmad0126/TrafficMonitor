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

    private function _get_period($period){
        $end = new DateTime('23:59:59');
        switch ($period) {
            case 'today':
                $start = new DateTime('00:00:00');
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

    public function get_traffic_in_period($period){
        $date = $this->_get_period($period);
        $start = $date['start'];
        $end = $date['end'];

        switch ($period) {
            case 'today':
                $query = DB::table($this->table)
                    ->selectRaw("DATE_FORMAT(tanggal, '%Y-%m-%d %H:00:00') AS tanggal, COUNT(*) AS jumlah")
                    ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
                    ->groupByRaw("DATE_FORMAT(tanggal, '%Y-%m-%d %H')")
                    ->get();
                $format = 'H:00';
                $increment = '+1 hour';
                break;
            case 'week':
                $query = DB::table($this->table)
                    ->selectRaw("DATE(tanggal) AS tanggal, COUNT(*) AS jumlah")
                    ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
                    ->groupByRaw("DATE(tanggal)")
                    ->get();
                $format = 'd M';
                $increment = '+1 day';
                break;
            case 'month':
                $query = DB::table($this->table)
                    ->selectRaw("DATE(tanggal) AS tanggal, COUNT(*) AS jumlah")
                    ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
                    ->groupByRaw("DATE(tanggal)")
                    ->get();
                $format = 'd M';
                $increment = '+1 day';
                break;
            default:
                $query = DB::table($this->table)
                    ->selectRaw("DATE_FORMAT(`tanggal`, '%Y-%m-01') AS tanggal, COUNT(*) AS jumlah")
                    ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
                    ->groupByRaw("DATE_FORMAT(`tanggal`, '%Y-%m')")
                    ->get();
                $format = 'M Y';
                $increment = '+1 month';
                break;
        }

        return $this->_collect($query, $start, $end, $format, $increment);
    }
    public function get_kendaraan_in_period($period){
        $date = $this->_get_period($period);
        return $this->_get_kendaraan_in_period($date['start'], $date['end']);
    }
    public function get_kecepatan_in_period($period){
        $date = $this->_get_period($period);
        return $this->_get_kecepatan_in_period($date['start'], $date['end']);
    }
    public function get_rata2_kecepatan($period){
        $date = $this->_get_period($period);
        return $this->_get_rata2_kecepatan($date['start'], $date['end']);
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
    private function _get_kecepatan_in_period($start, $end){
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
    private function _get_rata2_kecepatan($start, $end){
        $query = DB::table($this->table)
            ->selectRaw("AVG(kecepatan) as speed")
            ->whereRaw("tanggal BETWEEN '".$start->format('Y-m-d H:i:s')."' AND '".$end->format('Y-m-d H:i:s')."'")
            ->limit(1)
            ->get()->first();

        return $query->speed;
    }
}
