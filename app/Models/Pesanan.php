<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'pesanan';
    
    protected $fillable = [
        'tanggal_pemesanan', 
        'total_harga', 
        'alamat_pengiriman', 
        'metode_pembayaran',
        'status_pesanan',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function item_pesanan(){
        return $this->hasMany(ItemPesanan::class, 'pesanan_id');
    }

    public function pembayaran(){
        return $this->hasOne(Pembayaran::class, 'pesanan_id');
    }
}
