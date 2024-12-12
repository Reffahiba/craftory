@extends('layout.app')

@section('content')

<header class="flex items-center justify-between bg-white p-4 shadow-md">
    <div class="flex items-center space-x-3">
        <button id="menu-toggle" class="text-gray-700 mr-1 hover:bg-gray-300">
            <img src="{{ asset('assets/img/hamburger-menu.png') }}" alt="hamburger-menu" class="w-100 h-100">
        </button>
        <img src="{{ asset('assets/img/craftory-word.png') }}" alt="craftory-word" class="w-50 h-50 ml-5">
    </div>
    <div class="flex items-center space-x-4">
        <span class="text-gray-700">Halo, <?= $user->nama_user ?></span>
        <img src="{{ asset($user->foto_user) }}" alt="user-profile" class="w-10 h-10 rounded-full" id="gambar-profil">

        <div id="dropdown" class="hidden absolute mt-44 right-0 w-48 bg-white rounded-md shadow-lg z-10">
            <div class="py-2">
                <a href="{{ route('profile-admin') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-300">Profil</a>
                <form action="{{ route('logout-admin') }}" method="POST">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-300">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>

<div class="h-screen flex">
<!-- Sidebar -->
    <aside id="sidebar" class="hidden left-0 w-64 bg-light-apricot p-2">
        <div class="flex flex-col items-center">
            <img src="{{ asset('assets/img/image.png') }}" alt="craftory-icon" class="w-50 h-50 mt-1">
            <span class="text-lg font-semibold text-red-700">Craftory</span>
        </div>
        <nav class="w-62 shadow-md">
            <div class="flex flex-col space-y-3">
                <a href="{{ route('dashboard-penjual') }}" class="flex items-center space-x-1 p-3 rounded bg-rust text-white font-medium">
                    <span>🏠</span><span>Home</span>
                </a>
                <a href="{{ route('data-produk') }}" class="flex items-center space-x-1 p-3 rounded text-purple-brown font-medium hover:bg-red-200">
                    <span>📦</span><span>Produk</span>
                </a>
                <a href="{{ route('data-toko') }}" class="flex items-center space-x-1 p-3 rounded text-purple-brown font-medium hover:bg-red-200">
                    <span>🛒</span><span>Pesanan</span>
                </a>
                <a href="#" class="flex items-center space-x-1 p-3 rounded text-purple-brown font-medium hover:bg-red-200">
                    <span>📊</span><span>Laporan</span>
                </a>
                <a href="/pengaturan_toko" class="flex items-center space-x-1 p-3 rounded text-purple-brown font-medium hover:bg-red-200">
                    <span>⚙️</span><span>Pengaturan Toko</span>
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 transition-all duration-300 p-3" id="main-content">
        <main class="container mx-auto p-6 flex space-x-6">

            <!-- Content -->
            <div class="flex-1 bg-white shadow rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:space-x-6">
                    <form action="{{ route('profile-update-admin', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data" class="md:w-2/3 space-y-4">
                        @csrf
                        <div class="flex flex-col items-center w-full md:w-1/3">
                            <div class="w-44 h-44 border-2 border-gray-300 rounded-full flex items-center justify-center mb-4">
                                <img src="{{ asset($user->foto_user) }}" alt="user-profile" class="w-44 h-44 rounded-full" id="gambar-profil">
                            </div>
                            <input type="file" name="foto_user" id="foto_user" class="block w-full rounded-lg py-2 px-2 border border-gray-300">
                            <p class="text-sm text-gray-600 mt-2 text-center">Besar file: maksimum 10 MB. <br> Ekstensi file yang diperbolehkan: JPG, JPEG, PNG</p>
                        </div>
                        <div>
                            <label for="nama_user" class="block text-gray-700 font-medium mb-1">Nama</label>
                            <input type="text" name="nama_user" id="nama_user" class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rust" value="{{ old('nama_user', $user->nama_user) }}"">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                            <input type="email" name="email" id="email" class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rust" value="{{ old('email', $user->email) }}"">
                        </div>
                        <div>
                            <label for="no_telepon" class="block text-gray-700 font-medium mb-1">Nomor Telepon</label>
                            <input type="text" name="no_telepon" id="no_telepon" class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rust" value="{{ old('no_telepon', $user->no_telepon) }}">
                        </div>
                        <button type="submit" class="bg-rust text-white px-4 py-2 rounded-lg font-medium hover:bg-rust-dark">Ubah</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
        


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gambarProfile = document.getElementById('gambar-profil');
        const dropDown = document.getElementById('dropdown');

        gambarProfile.addEventListener('click', () =>{
            dropDown.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if(!gambarProfile.contains(event.target) && !dropDown.contains(event.target)){
                dropDown.classList.add('hidden');
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const menuicon = document.getElementById('menu-icon');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('hidden'); // Toggles sidebar visibility

            if(sidebar.classList.contains('block')){
                mainContent.classList.add('ml-64');
            } else {
                mainContent.classList.remove('ml-64'); // Shifts main content when sidebar is shown/hidden
            }
        });
    });
</script>

@endsection