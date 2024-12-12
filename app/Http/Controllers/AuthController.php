<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\Role;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    public $role;
    public $userModel;

    public function __construct(){
        $this->role = new Role();
        $this->userModel = new UserModel();
    }

    public function admin_login(){
        return view('admin-login');
    }

    public function admin_register(){
        $roleModel = new Role();
        $role = $roleModel->getRole();

        if(!$role->contains('nama_role', 'admin')){
            $role->push(new Role(['nama_role' => 'admin']));
        }

        $data = [
            'title' => 'Register Admin',
            'role' => $role
        ];

        return view('admin-register', $data);
    }

    public function admin_register_proses(Request $request){
        $request->validate([
            'nama_user' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email',
            'no_telepon' => 'required|string|regex:/^[0-9]+$/|max:15',
            'password' => 'required|string|confirmed|min:8',
            'foto_user' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->hasFile('foto_user')){
            $foto_user = $request->file('foto_user');
            $filename = time() . '_' . $foto_user->getClientOriginalName();
            $fotoPath = $foto_user->move(('user/img/'), $filename);
        } else {
            $fotoPath = null;
        }

        $user = UserModel::create([
            'nama_user' => $request->input('nama_user'),
            'email' => $request->input('email'),
            'no_telepon' => $request->input('no_telepon'),
            'password' => bcrypt($request->input('password')),
            'role_id' => 1,
            'foto_user' => 'user/img/' . $filename,
        ]);

        return redirect()->route('login-admin');
    }

    public function admin_login_proses(Request $request){
        if (Auth::check() && Auth::user()->role_id === 1) {
            return redirect('admin/dashboard_admin');
        }
        
        $kredensial = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = UserModel::where('email', $kredensial['email'])->first();

        // Periksa apakah pengguna ditemukan dan role_id sesuai
        if (!$user || !in_array($user->role_id, [1])) {
            return back()->withErrors([
                'email' => 'Akses hanya diperbolehkan untuk admin.',
            ]);
        }

        // Periksa kredensial
        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role_id
            if ($user->role_id === 1) {
                return redirect()->intended('admin/dashboard_admin');
            }
        }

        // Jika autentikasi gagal
        return back()->withErrors([
            'email' => 'Kredensial yang dimasukkan tidak sesuai.',
        ]);
    }

    public function logout_admin(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin_login')->with('sukses', 'Kamu berhasil logout');
    }

    // public function verifikasi_toko($id){
    //     $user = UserModel::findOrFail($id);

    //     if($user->role_id != 2){
    //         return redirect()->back()->with('error', 'User ini bukan penjual');
    //     }

    //     if($user->toko){
    //         $user->toko->status_verifikasi = 'terverifikasi';
    //         $user->toko->save();

    //         return redirect()->back()->with('sukses', 'Toko berhasil diverifikasi');
    //     }

    //     return redirect()->back()->with('sukses', 'Toko tidak ditemukan');
    // }

    // public function tolak_verifikasi_toko($id){
    //     $user = UserModel::findOrFail($id);

    //     if($user->role_id != 2){
    //         return redirect()->back()->with('error', 'User ini bukan penjual');
    //     }

    //     if($user->toko){
    //         $user->toko->status_verifikasi = 'ditolak';
    //         $user->toko->save();

    //         return redirect()->back()->with('sukses', 'Toko ditolak!');
    //     }

    //     return redirect()->back()->with('sukses', 'Toko tidak ditemukan');
    // }

}
