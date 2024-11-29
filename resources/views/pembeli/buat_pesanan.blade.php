@extends('layout.app')

@section('content')

    <header class="flex items-center justify-between bg-white p-4 shadow-md">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('assets/img/craftory-word.png') }}" alt="craftory-word" class="w-50 h-50 ml-5">
        </div>
        <div class="flex items-center space-x-4">
            <div class="flex flex-col items-center mr-10">
                <a href="#"><img src="{{ asset('assets/img/Cart1 with buy.png') }}" alt="keranjang" class="w-8 h-8"></a>
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

    <div class="flex flex-col flex-1 transition-all duration-300 p-3">
        <main class="container mx-auto p-6 flex space-x-6">
            <!-- Content -->
            <div class="flex-1 bg-white shadow rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Buat Pesanan Baru</h2>
                <form action="{{ route('simpan-pesanan') }}" method="POST">
                    @csrf
                    <div>
                        <label for="alamat_pengiriman" class="block text-sm font-semibold text-gray-700">Alamat Pengiriman</label>
                        <input type="text" name="alamat_pengiriman" id="alamat_pengiriman" class="mt-2 w-full p-3 border border-gray-300 rounded-md" required>
                    </div>
                    <button type="submit" class="mt-6 w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">Buat Pesanan</button>
                </form>
            </div>
        </main>
    </div>


@endsection