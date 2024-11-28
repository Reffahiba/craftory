<?php

namespace App\Http\Controllers;

use App\Models\ItemPesanan;
use App\Models\Kategori;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\UserModel;
use Illuminate\Http\Request;
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

    public function dashboard_pembeli(){
        $userId = Auth::guard('pembeli')->id();
        $pesanan = Pesanan::where('user_id', Auth::id())->latest()->first();

        $data = [
            'title' => 'Dashboard Pembeli',
            'user' => $this->userModel->getUser($userId),
            'produk' => $this->produk->getProduk(),
            'pesanan' => $pesanan,
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

    public function buat_pesanan(Request $request){
        return view('pembeli/buat_pesanan');
    }

    public function simpan_pesanan(Request $request){
        $request->validate([
            'alamat_pengiriman' => 'required|string|max:255',
            'metode_pembayaran' => 'required|string',
        ]);

        // Buat pesanan baru
        $pesanan = Pesanan::create([
            'user_id' => Auth::id(),
            'status_pesanan' => 'pending',
            'tanggal_pemesanan' => now(),
            'alamat_pengiriman' => $request->alamat_pengiriman,
            'metode_pembayaran' => $request->metode_pembayaran,
            'total_harga' => 0,
        ]);

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
        $pesanan = Pesanan::firstOrCreate(
            ['user_id' => Auth::id(), 'status_pesanan' => 'pending'],
            ['tanggal_pemesanan' => now(), 'total_harga' => 0]
        );

        if(!$pesanan){
            return redirect()->route('buat_pesanan');
        }

        $itemPesanan = ItemPesanan::firstOrNew([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $produk->id,
        ]);

        // Cek jika produk sudah ada di keranjang
        $keranjang = ItemPesanan::where('produk_id', $produk->id)->where('pesanan_id', $pesanan->id)->first();

        if ($keranjang) {
            // Jika sudah ada, tambahkan jumlahnya
            $keranjang->kuantitas += 1;
            $keranjang->sub_total = $keranjang->kuantitas * $produk->harga;
            $keranjang->save();
        } else {
            ItemPesanan::create([
                'user_id' => Auth::id(),
                'pesanan_id' => $pesanan->id,
                'produk_id' => $produk->id,
                'kuantitas' => 1,
                'sub_total' => $produk->harga,
            ]);
        }

        $pesanan->total_harga = ItemPesanan::where('pesanan_id', $pesanan->id)->sum('sub_total');
        $pesanan->save();

        return redirect()->route('keranjang'); // Redirect ke halaman keranjang
    }

    public function update_keranjang(Request $request, $id, $action)
    {
        $keranjang = ItemPesanan::findOrFail($id);
        $produk = $keranjang->produk;

        if ($action == 'tambah') {
            $keranjang->kuantitas += 1;
        } elseif ($action == 'kurang' && $keranjang->kuantitas > 1) {
            $keranjang->kuantitas -= 1;
        }

        // Update subtotal
        $keranjang->sub_total = $keranjang->kuantitas * $produk->harga;
        $keranjang->save();

        return back(); 
    }

    public function hapus_keranjang($id)
    {
        $keranjang = ItemPesanan::findOrFail($id);
        $keranjang->delete();

        return back();
    }

    // public function checkout(Request $request)
    // {
    //     // Ambil data pesanan dan total harga dari session atau database
    //     $totalHarga = $request->input('total_harga');
    //     $pesananId = $request->input('pesanan_id');

    //     // Buat transaksi
    //     $transactionDetails = [
    //         'order_id' => 'ORDER-' . time(),
    //         'gross_amount' => $totalHarga, // Total pembayaran
    //     ];

    //     // Data pemesan
    //     $customerDetails = [
    //         'first_name' => 'John',
    //         'last_name' => 'Doe',
    //         'email' => 'customer@example.com',
    //         'phone' => '08123456789',
    //         'billing_address' => [
    //             'address' => 'Jl. Example No. 1',
    //             'city' => 'Jakarta',
    //             'postal_code' => '12345',
    //             'phone' => '08123456789',
    //         ],
    //     ];

    //     // Data untuk transaksi Midtrans
    //     $transaction = [
    //         'payment_type' => 'credit_card', // Jenis pembayaran, bisa disesuaikan
    //         'credit_card' => [
    //             'secure' => true, // Jika ingin menggunakan 3D Secure
    //         ],
    //         'transaction_details' => $transactionDetails,
    //         'customer_details' => $customerDetails,
    //     ];

    //     // Kirim data ke Midtrans
    //     try {
    //         $snapToken = Snap::getSnapToken($transaction);
    //         return view('checkout', compact('snapToken', 'pesananId'));
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()]);
    //     }
    // }
}
