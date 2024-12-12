<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\Role;
use App\Models\Toko;
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

    public function login_proses(Request $request){
        if (Auth::check()) {
            $role = Auth::user()->role_id;

            if ($role === 2) {
                return redirect('penjual/dashboard_penjual');
            } else if ($role === 3) {
                return redirect('pembeli/dashboard_pembeli');
            }
        }
        
        $kredensial = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Cari pengguna berdasarkan email
        $user = UserModel::where('email', $kredensial['email'])->first();

        // Periksa apakah pengguna ditemukan dan role_id sesuai
        if (!$user || !in_array($user->role_id, [2, 3])) {
            return back()->withErrors([
                'email' => 'Akses hanya diperbolehkan untuk penjual dan pembeli.',
            ]);
        }

        // Periksa kredensial
        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role_id
            if ($user->role_id === 2) {
                return redirect()->intended('penjual/dashboard_penjual');
            } elseif ($user->role_id === 3) {
                return redirect()->intended('pembeli/dashboard_pembeli');
            }
        }

        // Jika autentikasi gagal
        return back()->withErrors([
            'email' => 'Kredensial yang dimasukkan tidak sesuai.',
        ]);
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
            'foto_user' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('foto_user')){
            $foto_user = $request->file('foto_user');
            $filename = time() . '_' . $foto_user->getClientOriginalName();
            $fotoPath = $foto_user->move(('user/img/'), $filename);
            $validateData['foto_user'] = 'user/img/' . $filename;
        } else {
            $fotoPath = null;
        }

        $validateData['password'] = bcrypt($validateData['password']);
        $user = UserModel::create($validateData);
        $user->load('role');

        if($validateData['role_id'] == 2){
            return redirect()->route('register-toko', ['user_id' => $user->id]);
        }
        
        return view('login');
    }

    public function register_toko(Request $request){
        $user_id = $request->input('user_id');
        $user = UserModel::find($user_id);

        if(!$user){
            return redirect()->route('register');
        }

        return view('register_toko',['user_id' => $user_id]);
    }

    public function register_toko_proses(Request $request){
        $validateData = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'alamat_toko' => 'required|string|max:255',
        ]);

        $user_id = $request->input('user_id');

        $toko = new Toko();
        $toko->nama_toko = $validateData['nama_toko'];
        $toko->alamat_toko = $validateData['alamat_toko'];
        $toko->status_verifikasi = 'menunggu';
        $toko->user_id = $user_id;
        $toko->save();
        
        return view('login');

    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('sukses', 'Kamu berhasil logout');
    }
}
