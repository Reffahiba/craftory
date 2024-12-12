<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AdminController extends Controller
{
    public $userModel;

    public $kategori;

    public $produk;

    public $toko;

    public function __construct(){
        $this->userModel = new UserModel();
        $this->kategori = new Kategori();
        $this->produk = new Produk();
        $this->toko = new Toko();
    } 

    public function dashboard_admin(){
        $jumlahKategori = Kategori::count();
        $jumlahToko = Toko::count();

        $data = [
            'title' => 'Dashboard Admin',
            'jumlahKategori' => $jumlahKategori,
            'jumlahToko' => $jumlahToko,
        ];

        return view('admin/dashboard_admin', $data);
    }

    public function profile_admin(){
        $data = [
            'title' => 'Profile Admin',
            'user' => $this->userModel->getUser(),
        ];

        return view('admin/profile_admin', $data);
    }

    public function edit_profile_admin($id){
        $user = UserModel::findOrFail($id);
        return  view('admin/edit_profile', compact('user'));
    }

    public function update_profile_admin(Request $request, $id){
        $user = UserModel::findOrFail($id);
        $user->nama_user = $request->nama_user;
        $user->email = $request->email;

        if($request->hasFile('foto_user')){
            $fileName = time() . '.' . $request->foto_user->extension();
            $request->foto_user->move(public_path('user/img/'), $fileName);
            $user->foto_user = 'user/img/' . $fileName;
        }

        $user->save();

        return redirect()->route('profile-admin');
    }

    public function edit_password_admin($id){
        $user = UserModel::findOrFail($id);
        $title = 'Ubah Password';
        return view ('admin/edit_password', compact('user', 'title'));
    }

    public function update_password_admin(Request $request, $id){
        $request->validate([
            'old_pass' => 'required',
            'new_pass' => 'required|confirmed|min:8',
            'confirm_pass' => 'required',
        ]);
        
        $user = UserModel::findOrFail($id);
        $decryptedPassword = Crypt::decrypt($user->password);

        if($decryptedPassword === $request->input('old_pass')){
            $user->password = Hash::make($request->new_pass);
            $user->save();
        } else {
            return redirect()->back()->withErrors(['old_pass' => 'Password lama tidak sesuai.']);
        }

        return redirect()->back();
    }

    public function data_kategori(){
        $data = [
            'title' => 'Data Kategori',
            'user' => $this->userModel->getUser(),
            'kategori' => $this->kategori->getKategori(),
        ];

        return view('admin/data_kategori', $data);
    }

    public function tambah_kategori(){
        $data = [
            'title' => 'Tambah Kategori',
        ];

        return view('admin/tambah_kategori', $data);
    }

    public function tambah_kategori_proses(Request $request){
        $validateData = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
            'deskripsi' => 'required|string|max:255',
        ]);

        $lastId = Kategori::max('id'); 
        $newId = $lastId ? $lastId + 1 : 1;

        $kategori = new Kategori();
        $kategori->id = $newId; 
        $kategori->nama_kategori = $validateData['nama_kategori'];
        $kategori->deskripsi = $validateData['deskripsi'];
        $kategori->save();

        return redirect()->route('data-kategori')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit_kategori($id){
        $kategori = Kategori::findOrFail($id);
        return  view('admin/edit_kategori', compact('kategori'));     
    }

    public function update_kategori(Request $request, $id){
        $kategori = Kategori::findOrFail($id);
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->deskripsi = $request->deskripsi;

        $kategori->save();

        return redirect()->route('data-kategori');
    }

    public function delete_kategori($id){
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('data-kategori');
    }

    public function data_toko(){
        $toko = Toko::all();
        
        $data = [
            'title' => 'Data Toko',
            'user' => $this->userModel->getUser(),
            'toko' => $toko,
        ];

        return view('admin/data_toko', $data);
    }

    public function edit_toko($id){
        $title = 'Edit Toko';
        $toko = Toko::findOrFail($id);
        return  view('admin/edit_toko', compact('title', 'toko'));     
    }

    public function update_toko(Request $request, $id){
        $toko = Toko::findOrFail($id);
        $toko->status_verifikasi = $request->status_verifikasi;

        $toko->save();

        return redirect()->route('data-toko');
    }
}
