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
                        <a href="{{ route('dashboard-admin') }}" class="flex items-center space-x-1 p-3 rounded text-purple-brown font-medium hover:bg-red-200">
                            <span>🏠</span><span>Home</span>
                        </a>
                        <a href="{{ route('data-kategori') }}" class="flex items-center space-x-1 p-3 rounded bg-rust  text-white font-medium">
                            <span>📦</span><span>Kategori</span>
                        </a>
                        <a href="{{ route('data-toko') }}" class="flex items-center space-x-1 p-3 rounded text-purple-brown font-medium hover:bg-red-200">
                            <span>🛒</span><span>Toko</span>
                        </a>
                    </div>
                </nav>
            </aside>

            <!-- Main Content -->
            <div class="flex flex-col flex-1 transition-all duration-300 p-3" id="main-content">
                <!-- Header -->
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-semibold">Daftar Kategori</h1>
                    <button class="bg-rust text-white px-4 py-2 rounded hover:bg-red-600"><a href="{{ route('tambah-kategori', ['id' => $user->id]) }}">+ Tambah Kategori Baru</a></button>
                </div>

                <!-- Search Bar -->
                <div class="flex items-center mb-4">
                    <input type="text" placeholder="Cari nama kategori" class="border rounded p-2 w-full max-w-xs">
                    <button class="ml-2 text-gray-500">🔍</button>
                </div>

                <div class="overflow-auto bg-white shadow-lg rounded-lg">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th>Nama Kategori</th>
                                <th>Deskripsi Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($kategori as $kategoriItem)
                                <tr class="border-b">
                                    <td class="py-3 px-6">{{ $kategoriItem->nama_kategori }}</td>
                                    <td class="py-3 px-6">{{ $kategoriItem->deskripsi }}</td>
                                    <td class="py-3 px-6">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('edit-kategori', ['id' => $kategoriItem->id]) }}" 
                                                class="bg-yellow-300 hover:bg-yellow-500 text-black font-semibold py-1 px-3 rounded-lg transition">
                                                Edit
                                            </a>
                                            <form action="{{ route('hapus-kategori', $kategoriItem->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-600 text-black font-semibold py-1 px-3 rounded-lg transition"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <!-- Pagination -->
                <div class="flex justify-center mt-6">
                    {{ $products->links() }}
                </div> --}}
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