<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
