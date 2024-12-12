@extends('layout.app')

@section('content')

<header class="flex items-center justify-between bg-white p-4 shadow-md">
    <div class="flex items-center space-x-3">
        <a href="{{ route('dashboard-admin') }}" class="text-gray-700 mr-1 hover:bg-gray-300">
            <img src="{{ asset('assets/img/back.png') }}" alt="hamburger-menu" class="w-100 h-100">
        </a>
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
    <!-- Main Content -->
    <div class="flex flex-col flex-1 transition-all duration-300 p-3" id="main-content">
        <main class="container mx-auto p-6 flex space-x-6">
            <!-- Sidebar -->
            <aside class="w-64 bg-white shadow rounded-lg p-4">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center">
                        <img src="{{ asset($user->foto_user) }}" alt="user-profile" class="w-20 h-20 rounded-full" id="gambar-profil">
                    </div>
                    <h2 class="text-xl font-semibold text-gray-700"><?= $user->nama_user ?></h2>
                </div>
                <nav class="space-y-2">
                    <a href="{{ route('profile-admin') }}" class="block p-3 bg-rust text-white rounded-lg font-medium hover:bg-rust-dark">Biodata Diri</a>
                </nav>
            </aside>

            <!-- Content -->
            <div class="flex-1 bg-white shadow rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:space-x-6">
                    <div class="flex flex-col items-center w-full md:w-1/3">
                        <div class="w-56 h-56 mt-6 border-2 border-gray-300 rounded-full flex items-center justify-center mb-4">
                            <img src="{{ asset($user->foto_user) }}" alt="user-profile" class="w-56 h-56 rounded-full" id="gambar-profil">
                        </div>
                    </div>

                    <div class="md:w-2/3 space-y-4">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-1">Password Lama</label>
                            <input type="text" id="name" class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rust" value="{{ old('nama_user', $user->nama_user) }}">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-1">Password Baru</label>
                            <input type="email" id="email" class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rust" value="{{ old('email', $user->email) }}">
                        </div>
                        <div>
                            <label for="phone" class="block text-gray-700 font-medium mb-1">Konfirmasi Password Baru</label>
                            <input type="text" id="phone" class="w-full border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rust" value="{{ old('no_telepon', $user->no_telepon) }}">
                        </div>
                        <button type="submit" class="bg-rust text-white px-4 py-2 rounded-lg font-medium hover:bg-rust-dark"><a href="{{ route('edit-profile-admin', ['id' => $user->id]) }}">Ubah Biodata</a></button>
                    </div>
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

@endsection