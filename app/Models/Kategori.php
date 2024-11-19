<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'kategori';
    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function getKategori(){
        return $this->all();
    }
}
