<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Toko;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;

class PenjualController extends Controller
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

    public function dashboard_penjual(){
        $userId = Auth::id();
        $toko = Toko::where('user_id', $userId)->first();
        $produk = Produk::where('toko_id', $toko->id)->get();
        $jumlahProduk = $produk->count();

        $data = [
            'title' => 'Dashboard Penjual',
            'jumlahProduk' => $jumlahProduk,
            'toko' => $toko,
        ];

        return view('penjual/dashboard_penjual', $data);
    }

    public function profile_penjual(){
        $userId = Auth::id();
        $toko = Toko::where('user_id', $userId)->first();
        
        $data = [
            'title' => 'Profile Penjual',
            'user' => $this->userModel->getUser(),
            'toko' => $toko,
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

    public function data_produk(){
        $userId = Auth::id();
        $toko = Toko::where('user_id', $userId)->first();
        

        $data = [
            'title' => 'Dashboard Penjual',
            'user' => $this->userModel->getUser($userId),
            'produk' => $this->produk->getProdukByPenjual($userId),
            'toko' => $toko,
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

        $toko = Toko::where('user_id', Auth::id())->first();
        if (!$toko) {
            return redirect()->back()->withErrors(['error' => 'Toko tidak ditemukan!']);
        }

        $produk = new Produk();
        $produk->id = $newId;
        $produk->foto_produk = 'produk/img/' . $filename;
        $produk->nama_produk = $validateData['nama_produk'];
        $produk->deskripsi = $validateData['deskripsi'];
        $produk->harga = $validateData['harga'];
        $produk->stok = $validateData['stok'];
        $produk->toko_id = $toko->id;
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
}
