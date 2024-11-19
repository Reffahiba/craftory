<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public $userModel;

    public $kategori;

    public $produk;

    public function __construct(){
        $this->userModel = new UserModel();
        $this->kategori = new Kategori();
        $this->produk = new Produk();
    } 

    public function dashboard_admin(){
        $data = [
            'title' => 'Dashboard Admin',
            'user' => $this->userModel->getUser(),
            'kategori' => $this->kategori->getKategori(),
        ];

        return view('dashboard_admin', $data);
    }
    public function dashboard_penjual(){
        $data = [
            'title' => 'Dashboard Penjual',
            'user' => $this->userModel->getUser(),
            'produk' => $this->produk->getProduk(),
        ];

        return view('dashboard_penjual', $data);
    }

    public function tambah_kategori(){
        $data = [
            'title' => 'Tambah Kategori',
        ];

        return view('tambah_kategori', $data);
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

        return redirect()->route('dashboard-admin')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit_kategori($id){
        $kategori = Kategori::findOrFail($id);
        return  view('edit_kategori', compact('kategori'));     
    }

    public function update_kategori(Request $request, $id){
        $kategori = Kategori::findOrFail($id);
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->deskripsi = $request->deskripsi;

        $kategori->save();

        return redirect()->route('dashboard-admin');
    }

    public function delete_kategori($id){
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('dashboard-admin');
    }

    public function tambah_produk(){
        $data = [
            'title' => 'Tambah Produk',
            'kategori' => $this->kategori->getKategori(),
            'user' => $this->userModel->getUser(),
        ];

        return view('tambah_produk', $data);
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

        return redirect()->route('dashboard-penjual')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit_produk($id){
        $produk = Produk::findOrFail($id);
        $kategori = $this->kategori->getKategori();
        return  view('edit_produk', compact('produk', 'kategori'));     
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

        return redirect()->route('dashboard-penjual');
    }

    public function delete_produk($id){
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('dashboard-penjual');
    }
}
