<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\alert;

class LoginController extends Controller
{
    public $role;
    public $userModel;

    public function __construct(){
        $this->role = new Role();
        $this->userModel = new UserModel();
    }

    public function login(){
        return view('login');
    }


    public function register(){
        $roleModel = new Role();
        $role = $roleModel->getRole();

        $data = [
            'title' => 'Register',
            'role' => $role
        ];

        return view('register', $data);
    }

    public function register_proses(Request $request){
        $validateData = $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'no_telepon' => 'required|string|regex:/^[0-9]+$/|max:15',
            'password' => 'required|string|confirmed|min:8',
            'role_id' => 'required|integer',
        ]);

        $validateData['password'] = bcrypt($validateData['password']);
        $user = UserModel::create($validateData);
        $user->load('role');

        return view('login');
    }

    public function login_proses(Request $request){
        $kredensial = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if(Auth::attempt($kredensial)){
            $request->session()->regenerate();

            $role = Auth::user()->role_id;

            if($role === 2){
                return redirect()->intended('/dashboard_penjual');
            } else if($role === 3){
                return redirect()->intended('/dashboard_pembeli');
            }
        }

        return back()->withErrors([
            'email' => 'Kredensial yang dimasukkan tidak sesuai.',
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('sukses', 'Kamu berhasil logout');
    }
}
