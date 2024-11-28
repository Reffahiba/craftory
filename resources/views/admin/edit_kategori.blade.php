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
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-300">Profil</a>
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-300">Pengaturan</a>
                        <form action="{{ route('logout-admin') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-300">Keluar</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <h1 class="text-3xl font-semibold mt-5 ml-3">Edit Kategori</h1>
        <div class="flex-grow bg-white p-5">
            <div class="bg-light-apricot rounded-xl shadow-2xl p-5">

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li class="text-red-600">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('kategori-update', $kategori['id']) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="nama_kategori" class="block font-bold pb-1">Nama Kategori:</label>
                        <input type="text" name="nama_kategori" id="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" class="block w-full rounded-lg py-2 px-2 border border-gray-300">
                        @foreach ($errors->get('nama_kategori') as $msg )
                            <p class="text-red-600">{{ $msg }}</p>
                        @endforeach
                    </div>

                    <div>
                        <label for="deskripsi" class="block font-bold pb-1">Deskripsi:</label>
                        <input type="text" name="deskripsi" id="deskripsi" value="{{ old('deskripsi', $kategori->deskripsi) }}" class="block w-full rounded-lg py-2 px-2 border border-gray-300">
                        @foreach ($errors->get('deskripsi') as $msg )
                            <p class="text-red-600">{{ $msg }}</p>
                        @endforeach
                    </div>

                    <div class="flex">
                        <button type="submit" class="rounded-lg bg-rust px-4 py-2 mr-2 font-bold text-white">Submit</button>
                        <button class="rounded-lg bg-purple-brown px-4 py-2 font-bold text-white"><a href="{{ route('data-kategori') }}">Back</a></button>
                    </div>
                </form>
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