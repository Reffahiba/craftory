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

    <div class="flex flex-col flex-1 transition-all duration-300 p-3" id="main-content">
        <main class="container mx-auto p-6 flex space-x-6">
            <!-- Sidebar -->
            <aside class="w-64 bg-white shadow-lg rounded-lg p-4">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
                        <img src="{{ asset($user->foto_user) }}" alt="user-profile" class="w-20 h-20 rounded-full" id="gambar-profil">
                    </div>
                    <h2 class="text-xl font-semibold text-gray-700"><?= $user->nama_user ?></h2>
                </div>
                <nav class="space-y-2">
                    <a href="{{ route('profile-pembeli') }}" class="block p-3  text-gray-700 rounded-lg font-medium hover:bg-gray-100">Biodata Diri</a>
                    <a href="{{ route('daftar-transaksi') }}" class="block p-3  bg-rust text-white rounded-lg font-medium hover:bg-purple-brown">Daftar Transaksi</a>
                </nav>
            </aside>

            <div class="overflow-auto bg-white shadow-lg rounded-lg">
                <table class="min-w-full bg-white p-2">
                    @if ($transaksi->isNotEmpty())
                        <thead>
                            <tr>
                                <th class="p-3">Tanggal Pemesanan</th>
                                <th class="p-3">Tanggal Pembayaran</th>
                                <th class="p-3">Total Harga</th>
                                <th class="p-3">Alamat Pengiriman</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($transaksi as $transaksiItem)
                                <tr class="border-b">
                                    <td class="py-3 px-6">{{ $transaksiItem->tanggal_pemesanan }}</td>
                                    <td class="py-3 px-6">{{ $transaksiItem->tanggal_pembayaran }}</td>
                                    <td class="py-3 px-6">{{ $transaksiItem->total_harga }}</td>
                                    <td class="py-3 px-6">{{ $transaksiItem->alamat_pengiriman }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    @else
                        <tr>
                            <td colspan="4" class="flex items-center py-3 px-6 text-center text-black font-bold">Belum ada transaksi.</td>
                        </tr>
                    @endif
                </table>
            </div>
        </main>
        {{-- <!-- Pagination -->
        <div class="flex justify-center mt-6">
            {{ $products->links() }}
        </div> --}}
    </div>

@endsection