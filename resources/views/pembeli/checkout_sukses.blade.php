@extends('layout.app')

@section('content')
<header class="flex items-center justify-between bg-white p-4 shadow-md">
    <div class="flex items-center space-x-3">
        <img src="{{ asset('assets/img/craftory-word.png') }}" alt="craftory-word" class="w-50 h-50 ml-5">
    </div>
    <div class="flex items-center space-x-4">
        <div class="flex flex-col items-center mr-10">
            <a href="{{ route('keranjang') }}">
                <img src="{{ asset('assets/img/Cart1 with buy.png') }}" alt="keranjang" class="w-8 h-8">
            </a>
            <span class="text-gray-700 text-sm">My Cart</span>
        </div>
        <span class="text-gray-700">Halo, <?= $user->nama_user ?></span>
        <img src="{{ asset($user->foto_user) }}" alt="user-profile" class="w-10 h-10 rounded-full" id="gambar-profil">

        <div id="dropdown" class="hidden absolute mt-44 right-0 w-48 bg-white rounded-md shadow-lg z-10">
            <div class="py-2">
                <a href="{{ route('profile-pembeli') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-300">Profil</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-300">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>

<div class="w-full max-w-4xl mx-auto p-6 bg-gray-100 rounded-lg shadow-lg mt-6">
    <div class="mb-6 border-b pb-4">
        <p class="text-lg text-gray-800 font-medium">
            <strong>Nomor Transaksi: </strong> 
            <span class="text-gray-600">{{ $pembayaran->id }}</span>
        </p>
        <p class="text-lg text-gray-800 font-medium">
            <strong>Total Pembayaran: </strong> 
            <span class="text-green-600">Rp{{ number_format($pembayaran->jumlah_dibayar, 0, ',', '.') }}</span>
        </p>
        <p class="text-lg text-gray-800 font-medium">
            <strong>Tanggal Pembayaran: </strong> 
            <span class="text-gray-600">{{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d M Y') }}</span>
        </p>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Detail Pesanan</h2>
        @if($pesanan && $pesanan->item_pesanan->isNotEmpty())
        <ul class="list-disc list-inside text-gray-600">
            @foreach($pesanan->item_pesanan as $item)
                <li class="mb-2">
                    <span class="font-medium text-gray-800">{{ $item->produk->nama_produk }}</span>
                    - Rp{{ number_format($item->sub_total, 0, ',', '.') }}
                </li>
            @endforeach
        </ul>
        @else
        <p class="text-gray-600 italic">Tidak ada item untuk pesanan ini.</p>
        @endif
    </div>

    <div class="flex justify-center">
        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition duration-200" id="pay-button">
            Bayar Sekarang
        </button>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function() {
        snap.pay('{{ $pembayaran->snap_token }}', {
            onSuccess: function(result) {
                window.location.href = '{{ route('dashboard-pembeli') }}';
            },
            onPending: function(result) {
                document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            },
            onError: function(result) {
                document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            }
        });
    };
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const menuicon = document.getElementById('menu-icon');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('hidden'); 

            if(sidebar.classList.contains('block')){
                mainContent.classList.add('ml-64');
            } else {
                mainContent.classList.remove('ml-64'); 
            }
        });
    });
</script>
@endsection
