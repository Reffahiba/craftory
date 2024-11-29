<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'toko_id',
        'kategori_id',
    ];

    public function kategori(){
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function toko(){
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    public function item_pesanan(){
        return $this->hasMany(ItemPesanan::class, 'produk_id');
    }

    public function getProduk(){
        return Produk::with('kategori')->get();
    }

    public function getProdukByPenjual($id){
    return Produk::whereExists(function ($query) use ($id) {
            $query->select(DB::raw(1)) // memilih kolom apa saja, misalnya 1
                ->from('toko')
                ->whereRaw('produk.toko_id = toko.id')
                ->where('toko.user_id', $id);
        })->get();
    }
}
