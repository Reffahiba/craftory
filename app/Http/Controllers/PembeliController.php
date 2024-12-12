<?php

namespace App\Http\Controllers;

use App\Models\ItemPesanan;
use App\Models\Kategori;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Midtrans\Snap;
// use Midtrans\Config;
use Illuminate\Support\Facades\Auth;

class PembeliController extends Controller
{
    public $userModel;

    public $kategori;

    public $produk;

    public $toko;

    public $pesanan;

    public function __construct(){
        $this->userModel = new UserModel();
        $this->kategori = new Kategori();
        $this->produk = new Produk();
        $this->toko = new Toko();

        // Config::$serverKey = config('midtrans.server_key');
        // Config::$clientKey = config('midtrans.client_key');
        // Config::$isProduction = config('midtrans.is_production');
        // Config::$isSanitized = true;
        // Config::$is3ds = true;
    }

    public function dashboard_pembeli(Request $request){
        $userId = Auth::guard('pembeli')->id();
        $pesanan = Pesanan::where('user_id', Auth::id())->latest()->first();
        $produk = Produk::whereExists(function ($query) {
            $query->select(DB::raw(1)) // memilih kolom apa saja, misalnya 1
                ->from('toko')
                ->whereRaw('produk.toko_id = toko.id')
                ->where('toko.status_verifikasi', 'terverifikasi');
        });

        $filterKategori = $request->filled('kategori');
        $filterHarga = $request->filled('min_harga') && $request->filled('max_harga') && ($request->min_harga > 0 || $request->max_harga > 0);
        
        if ($filterKategori) {
            $produk->whereHas('kategori', function ($q) use ($request) {
                $q->where('nama_kategori', 'like', '%' . $request->kategori . '%');
            });
        }

        if ($filterHarga) {
            $produk->whereBetween('harga', [$request->min_harga, $request->max_harga]);
        }

        $produk = $produk->get();

        $data = [
            'title' => 'Dashboard Pembeli',
            'user' => $this->userModel->getUser($userId),
            'produk' => $produk,
            'pesanan' => $pesanan,
            'kategori' => $this->kategori->getKategori(),
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
        $user->nama_user = $request->nama_user;
        $user->email = $request->email;
        $user->no_telepon = $request->no_telepon;

        if($request->hasFile('foto_user')){
            $fileName = time() . '.' . $request->foto_user->extension();
            $request->foto_user->move(public_path('user/img/'), $fileName);
            $user->foto_user = 'user/img/' . $fileName;
        }

        $user->save();

        return redirect()->route('profile-pembeli');
    }

    public function buat_pesanan(Request $request){
        return view('pembeli/buat_pesanan');
    }

    public function simpan_pesanan(Request $request){
        $request->validate([
            'alamat_pengiriman' => 'required|string|max:255',
        ]);

        // Buat pesanan baru
        $pesanan = Pesanan::create([
            'user_id' => Auth::id(),
            'status_pesanan' => 'pending',
            'tanggal_pemesanan' => now(),
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'total_harga' => 0,
        ]);

        if ($request->session()->has('produk_id')) {
            $produk = Produk::findOrFail($request->session()->get('produk_id'));
            $this->tambahProdukKeKeranjang($pesanan, $produk);

            // Hapus session setelah produk ditambahkan
            $request->session()->forget('produk_id');
        }

        // Redirect untuk menambahkan produk ke keranjang
        return redirect()->route('keranjang');
    }

    public function keranjang(){
        $pesanan = Pesanan::with('item_pesanan.produk')
            ->where('user_id', Auth::id())
            ->where('status_pesanan', 'pending')
            ->first();

        return view('pembeli/keranjang', compact('pesanan'));
    }

    public function tambah_keranjang(Request $request)
    {
        $produk = Produk::findOrFail($request->produk_id); // Mencari produk berdasarkan ID
        $pesanan = Pesanan::where('user_id', Auth::id())->where('status_pesanan', 'pending')->first();

        if(!$pesanan){
            $request->session()->put('produk_id', $produk->id);
            return redirect()->route('buat-pesanan');
        }

        $this->tambahProdukKeKeranjang($pesanan, $produk);

        return redirect()->route('keranjang'); // Redirect ke halaman keranjang
    }

    private function tambahProdukKeKeranjang($pesanan, $produk){
        $keranjang = ItemPesanan::firstOrNew([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
        ]);

        if ($keranjang->exists) {
            // Jika sudah ada, tambahkan jumlahnya
            $keranjang->kuantitas += 1;
            $keranjang->sub_total = $keranjang->kuantitas * $produk->harga;
            $keranjang->save();
        } else {
            // Jika belum ada, buat item baru
            ItemPesanan::create([
                'user_id' => Auth::id(),
                'pesanan_id' => $pesanan->id,
                'produk_id' => $produk->id,
                'kuantitas' => 1,
                'sub_total' => $produk->harga,
            ]);
        }

        // Update total harga pesanan
        $pesanan->total_harga = ItemPesanan::where('pesanan_id', $pesanan->id)->sum('sub_total');
        $pesanan->save();
    }

    public function update_keranjang(Request $request, $id, $action)
    {
        $keranjang = ItemPesanan::findOrFail($id);
        $produk = $keranjang->produk;
        $pesanan = $keranjang->pesanan; 

        if ($action == 'tambah') {
            $keranjang->kuantitas += 1;
        } elseif ($action == 'kurang' && $keranjang->kuantitas > 1) {
            $keranjang->kuantitas -= 1;
        }

        // Update subtotal
        $keranjang->sub_total = $keranjang->kuantitas * $produk->harga;
        $keranjang->save();

        $pesanan->total_harga = ItemPesanan::where('pesanan_id', $pesanan->id)->sum('sub_total');
        $pesanan->save();

        return back(); 
    }

    public function hapus_keranjang($id)
    {
        $keranjang = ItemPesanan::findOrFail($id);
        $pesanan = $keranjang->pesanan; 
        $keranjang->delete();

        $pesanan->total_harga = ItemPesanan::where('pesanan_id', $pesanan->id)->sum('sub_total');
        $pesanan->save();

        return back();
    }

    public function checkOut(){
        $pesanan = Pesanan::where('user_id', Auth::id())
            ->where('status_pesanan', 'pending')
            ->first();

        if ($pesanan) {
            // Proses checkout, misalnya ubah status pesanan
            $pesanan->update(['status_pesanan' => 'check out']);

            $pembayaran = Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'jumlah_dibayar' => $pesanan->item_pesanan->sum('sub_total'), // Total pembayaran
                'tanggal_pembayaran' => now(),
            ]);
        } 
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(), // Buat order_id yang unik
                'gross_amount' => $pesanan->item_pesanan->sum('sub_total'), // Total harga pesanan
            ),
            'customer_details' => array(
                'first_name' => Auth::user()->nama_user,
                'email' => Auth::user()->email,
            ),
        );

        $snapToken =  \Midtrans\Snap::getSnapToken($params);
        $pembayaran->snap_token = $snapToken;
        $pembayaran->save();
        
        return redirect()->route('checkOut-selesai', $pembayaran->id);
    }

    public function checkOut_selesai($id){
        $pembayaran = Pembayaran::find($id);
        if(!$pembayaran){
            return redirect()->route('keranjang');
        }

        $pesanan = $pembayaran->pesanan;
        

        $data = [
            'pembayaran' => $pembayaran,
            'pesanan' => $pesanan,
        ];

        return view('pembeli/checkout_sukses', $data);

    }

    public function daftar_transaksi(){
        $user_id = Auth::id();

        $transaksi = DB::table('pembayaran')
            ->join('pesanan', 'pesanan.id', '=', 'pembayaran.pesanan_id')
            ->select('pembayaran.*', 'pesanan.*')
            ->where('pesanan.user_id', $user_id)   
            ->get();
        
        $title = 'Data Transaksi';

        return view('pembeli/daftar_transaksi', compact('transaksi', 'title'));
    }
}
