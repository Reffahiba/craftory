<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $guarded = ['id'];
    protected $fillable = [
        'nama_user',
        'email', 
        'password', 
        'no_telepon', 
        'role_id',
    ];

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getUser($id = null){
        if(!$id == null){
            return $this->select('user.nama_user' )->where('user.id', $id)->first();
        }
    }
}
