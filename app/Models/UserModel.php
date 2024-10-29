<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;

    protected $guarded = ['id_user'];
    protected $table = 'user';
    protected $fillable = ['nama_user', 'email', 'password', 'no_telepon', 'id_role',];

    public function role(){
        return $this->belongsTo(Role::class, 'id_role');
    }
}
