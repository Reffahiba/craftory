<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'pembayaran';
    protected $fillable = [
        'tanggal_pembayaran',
        'jumlah_dibayar',
        'snap_token',
        'pesanan_id',
    ];

    public function pesanan(){
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
}
