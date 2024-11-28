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
        'pesanan_id',
    ];

    public function pesanan(){
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
