@extends('layout.app')

@section('content')
    <header class="flex items-center justify-between bg-white p-4 shadow-md">
        <div class="flex items-center space-x-3">
            <a href="{{ route('dashboard-pembeli') }}" class="text-gray-700 mr-1 hover:bg-gray-300">
                <img src="{{ asset('assets/img/back.png') }}" alt="hamburger-menu" class="w-100 h-100">
            </a>
            <img src="{{ asset('assets/img/craftory-word.png') }}" alt="craftory-word" class="w-50 h-50 ml-5">
        </div>
        <div class="flex items-center space-x-4">
            <div class="flex flex-col items-center mr-10">
                <a href="{{ route('keranjang') }}"><img src="{{ asset('assets/img/Cart1 with buy.png') }}" alt="keranjang" class="w-8 h-8"></a>
                <span class="text-gray-700 text-sm">My Cart</span>
            </div>
            <span class="text-gray-700">Halo, <?= $user->nama_user ?></span>
            <img src="{{ asset($user->foto_user) }}" alt="user-profile" class="w-10 h-10 rounded-full" id="gambar-profil">

            <div id="dropdown" class="hidden absolute mt-44 right-0 w-48 bg-white rounded-md shadow-lg z-10">
                <div class="py-2">
                    <a href="{{ route('profile-pembeli') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-300">Profil</a>
                    <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-300">Pengaturan</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-300">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="w-full max-w-3xl mx-auto p-6">
        <div class="mb-4">
            <p><strong>Nomor Transaksi: </strong>{{ $pembayaran->id }}</p>
            <p><strong>Total Pembayaran: </strong>Rp{{ number_format($pembayaran->jumlah_dibayar, 0, ',', '.') }}</p>
            <p><strong>Tanggal Pembayaran: </strong>{{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d M Y') }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-semibold">Detail Pesanan</h2>
            @if($pesanan && $pesanan->item_pesanan->isNotEmpty())
                <ul>
                    @foreach($pesanan->item_pesanan as $item)
                        <li>{{ $item->produk->nama_produk }} - Rp{{ number_format($item->sub_total, 0, ',', '.') }}</li>
                    @endforeach
                </ul>
            @else
                <p>Tidak ada item untuk pesanan ini.</p>
            @endif
        </div>

        <button type="button" class="bg-rust text-center font-medium text-white" id="pay-button">Bayar Sekarang</button>
        
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
            // SnapToken acquired from previous step
            snap.pay('{{ $pembayaran->snap_token }}', {
            // Optional
            onSuccess: function(result){
                /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            },
            // Optional
            onPending: function(result){
                /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            },
            // Optional
            onError: function(result){
                /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            }
            });
        };
    </script>    
@endsection
