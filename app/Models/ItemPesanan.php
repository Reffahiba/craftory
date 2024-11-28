<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'item_pesanan';
    protected $fillable = ['harga', 'kuantitas', 'sub_total', 'produk_id', 'pesanan_id'];

    public function produk(){
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function pesanan(){
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
    
    public function getItemPesanan(){
        return $this->all();
    }
}
