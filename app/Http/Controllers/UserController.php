<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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
        $user->foto_user = $request->foto_user;
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

    public function dashboard_penjual(){
        $jumlahProduk = Produk::count();

        $data = [
            'title' => 'Dashboard Penjual',
            'jumlahProduk' => $jumlahProduk,
        ];

        return view('penjual/dashboard_penjual', $data);
    }

    public function profile_penjual(){
        $data = [
            'title' => 'Profile Penjual',
            'user' => $this->userModel->getUser(),
        ];

        return view('penjual/profile_penjual', $data);
    }

    public function edit_profile_penjual($id){
        $user = UserModel::findOrFail($id);
        return  view('penjual/edit_profile', compact('user'));
    }

    public function update_profile_penjual(Request $request, $id){
        $user = UserModel::findOrFail($id);
        $user->foto_user = $request->foto_user;
        $user->nama_user = $request->nama_user;
        $user->email = $request->email;

        if($request->hasFile('foto_user')){
            $fileName = time() . '.' . $request->foto_user->extension();
            $request->foto_user->move(public_path('user/img/'), $fileName);
            $user->foto_user = 'user/img/' . $fileName;
        }

        $user->save();

        return redirect()->route('profile-penjual');
    }

    public function dashboard_pembeli(){
        $userId = Auth::guard('pembeli')->id();

        $data = [
            'title' => 'Dashboard Pembeli',
            'user' => $this->userModel->getUser($userId),
            'produk' => $this->produk->getProduk(),
        ];

        return view('pembeli/dashboard_pembeli', $data);
    }

    public function profile_pembeli(){
        $data = [
            'title' => 'Profile Pembeli',
            'user' => $this->userModel->getUser(),
        ];

        return view('pembeli/profile_pembeli', $data);
    }

    public function edit_profile_pembeli($id){
        $user = UserModel::findOrFail($id);
        return  view('pembeli/edit_profile', compact('user'));
    }

    public function update_profile_pembeli(Request $request, $id){
        $user = UserModel::findOrFail($id);
        $user->foto_user = $request->foto_user;
        $user->nama_user = $request->nama_user;
        $user->email = $request->email;

        if($request->hasFile('foto_user')){
            $fileName = time() . '.' . $request->foto_user->extension();
            $request->foto_user->move(public_path('user/img/'), $fileName);
            $user->foto_user = 'user/img/' . $fileName;
        }

        $user->save();

        return redirect()->route('profile-pembeli');
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

    public function data_produk(){
        $data = [
            'title' => 'Dashboard Penjual',
            'user' => $this->userModel->getUser(),
            'produk' => $this->produk->getProduk(),
        ];

        return view('penjual/data_produk', $data);
    }

    public function tambah_produk(){
        $data = [
            'title' => 'Tambah Produk',
            'kategori' => $this->kategori->getKategori(),
            'user' => $this->userModel->getUser(),
        ];

        return view('penjual/tambah_produk', $data);
    }

    public function tambah_produk_proses(Request $request){
        $validateData = $request->validate([
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nama_produk' => 'required|string|max:255|unique:produk,nama_produk',
            'deskripsi' => 'required|string|max:255',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
            'kategori_id' => 'required|integer',
        ]);

        if($request->hasFile('foto_produk')){
            $foto_produk = $request->file('foto_produk');
            $filename = time() . '_' . $foto_produk->getClientOriginalName();
            $fotoPath = $foto_produk->move(('produk/img/'), $filename);
        } else {
            $fotoPath = null;
        }

        $lastId = Produk::max('id'); 
        $newId = $lastId ? $lastId + 1 : 1;

        $produk = new Produk();
        $produk->id = $newId;
        $produk->foto_produk = 'produk/img/' . $filename;
        $produk->nama_produk = $validateData['nama_produk'];
        $produk->deskripsi = $validateData['deskripsi'];
        $produk->harga = $validateData['harga'];
        $produk->stok = $validateData['stok'];
        $produk->user_id = Auth::id();
        $produk->kategori_id = $validateData['kategori_id'];
        $produk->save();

        return redirect()->route('data-produk')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit_produk($id){
        $produk = Produk::findOrFail($id);
        $kategori = $this->kategori->getKategori();
        return  view('penjual/edit_produk', compact('produk', 'kategori'));     
    }

    public function update_produk(Request $request, $id){
        $produk = Produk::findOrFail($id);
        $produk->nama_produk = $request->nama_produk;
        $produk->deskripsi = $request->deskripsi;
        $produk->harga = $request->harga;
        $produk->stok = $request->stok;
        $produk->kategori_id = $request->kategori_id;

        if($request->hasFile('foto_produk')){
            $fileName = time() . '.' . $request->foto_produk->extension();
            $request->foto_produk->move(public_path('produk/img/'), $fileName);
            $produk->foto_produk = 'produk/img/' . $fileName;
        }

        $produk->save();

        return redirect()->route('data-produk');
    }

    public function delete_produk($id){
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('data-produk');
    }

    public function keranjang($id){
        $produk = Produk::findOrFail($id);

        $data = [
            
        ]
    }
}
