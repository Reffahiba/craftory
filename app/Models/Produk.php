<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $guarded = ['id'];
    protected $fillable = [
        'foto_produk',
        'nama_produk',
        'deskripsi',
        'harga',
        'stok',
        'user_id',
        'kategori_id',
    ];

    public function kategori(){
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function item_pesanan(){
        return $this->hasMany(ItemPesanan::class, 'produk_id');
    }

    public function getProduk(){
        return Produk::with('kategori')->get();
    }
}
