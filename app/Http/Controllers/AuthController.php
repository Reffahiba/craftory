<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\Role;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;

class AuthController extends Controller
{

    public $role;
    public $userModel;

    public function __construct(){
        $this->role = new Role();
        $this->userModel = new UserModel();
    }

    public function signIn(){
        return view('sign-in');
    }


    public function create(){
        $roleModel = new Role();
        $role = $roleModel->getRole();

        $data = [
            'title' => 'Register',
            'role' => $role
        ];

        return view('register', $data);
    }

    public function store(Request $request){
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|confirmed|max:255',
            'no_telepon' => 'required|string|max:255',
            'id_role' => 'required|integer'
        ]);

        $this->userModel->create([
            'nama_user' => $request->input('nama_user'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'no_telepon' => $request->input('no_telepon'),
            'id_role' => $request->input('id_role'),
        ]);

        return redirect()->route('sign-in')->with('success', 'User berhasil ditambahkan');
    }
}
