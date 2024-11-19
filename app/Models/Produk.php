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
        'id_user',
        'id_kategori',
    ];

    public function getProduk(){
        return $this->all();
    }
}
