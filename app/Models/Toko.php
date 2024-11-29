<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'toko';
    protected $fillable = ['nama_toko', 'status_verifikasi'];

    public function user(){
        return $this->belongsTo(UserModel::class);
    }

    public function produk(){
        return $this->hasMany(Produk::class, 'toko_id');
    }
}
