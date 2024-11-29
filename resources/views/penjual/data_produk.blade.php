<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?></title>
    <link rel="icon" href="{{ asset('assets/img/image.png') }}">
    @vite('resources/css/app.css')
</head>
<body>
    <header class="flex items-center justify-between bg-white p-4 shadow-md">
        <div class="flex items-center space-x-3">
            <button id="menu-toggle" class="text-gray-700 mr-1 hover:bg-gray-300">
                <img src="{{ asset('assets/img/hamburger-menu.png') }}" alt="hamburger-menu" class="w-100 h-100">
            </button>
            <img src="{{ asset('assets/img/craftory-word.png') }}" alt="craftory-word" class="w-50 h-50 ml-5">
        </div>
        <div class="flex items-center space-x-4">
            @if ($toko->status_verifikasi == 'terverifikasi')
                <img src="{{ asset('assets/img/tick-circle.png') }}" alt="terverifikasi" class="w-7 h-7">
            @elseif ($toko->status_verifikasi == 'menunggu')
                <img src="{{ asset('assets/img/info-circle.png') }}" alt="menunggu" class="w-7 h-7">
            @else
                <img src="{{ asset('assets/img/close-circle.png') }}" alt="ditolak" class="w-7 h-7">
            @endif
            <span class="text-gray-700">Halo, <?= $user->nama_user ?></span>
            <img src="{{ asset($user->foto_user) }}" alt="user-profile" class="w-10 h-10 rounded-full" id="gambar-profil">

            <div id="dropdown" class="hidden absolute mt-44 right-0 w-48 bg-white rounded-md shadow-lg z-10">
                <div class="py-2">
                    <a href="{{ route('profile-penjual') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-300">Profil</a>
                    <form action="{{ route('logout') }}" method="POST">
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
                    <a href="{{ route('dashboard-penjual') }}" class="flex items-center space-x-1 p-3 rounded text-purple-brown font-medium hover:bg-red-200">
                        <span>🏠</span><span>Home</span>
                    </a>
                    <a href="{{ route('data-produk') }}" class="flex items-center space-x-1 p-3 rounded bg-rust  text-white font-medium">
                        <span>📦</span><span>Produk</span>
                    </a>
                    <a href="#" class="flex items-center space-x-1 p-3 rounded text-purple-brown font-medium hover:bg-red-200">
                        <span>🛒</span><span>Pesanan</span>
                    </a>
                    <a href="/pengaturan_toko" class="flex items-center space-x-1 p-3 rounded text-purple-brown font-medium hover:bg-red-200">
                        <span>⚙️</span><span>Pengaturan Toko</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 transition-all duration-300 p-3" id="main-content">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-semibold">Daftar Produk</h1>
                <button class="bg-rust text-white px-4 py-2 rounded hover:bg-red-600"><a href="{{ route('tambah-produk', ['id' => $user->id]) }}">+ Tambah Produk Baru</a></button>
            </div>

            <!-- Search Bar -->
            <div class="flex items-center mb-4">
                <input type="text" placeholder="Cari nama produk" class="border rounded p-2 w-full max-w-xs">
                <button class="ml-2 text-gray-500">🔍</button>
            </div>

            <div class="overflow-auto bg-white shadow rounded-lg">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-200 text-gray-600">
                        <tr>
                            <th class="py-3 px-6 text-left">Foto Produk</th> 
                            <th class="py-3 px-6 text-left">Nama Produk</th>
                            <th class="py-3 px-6 text-left">Deskripsi</th>
                            <th class="py-3 px-6 text-left">Harga</th>
                            <th class="py-3 px-6 text-left">Stok</th>
                            <th class="py-3 px-6 text-left">Kategori</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach($produk as $produkItem)
                        <tr class="border-b">
                            <td class="py-3 px-6 flex items-center">
                                <img src="{{ asset($produkItem->foto_produk) }}" alt="Product Image" class="w-50 h-40 rounded">
                            </td>
                            <td class="py-3 px-6">{{ $produkItem->nama_produk }}</td>
                            <td class="py-3 px-6">{{ Str::limit($produkItem->deskripsi, 50) }}</td>
                            <td class="py-3 px-6">Rp{{ number_format($produkItem->harga, 0, ',', '.') }}</td>
                            <td class="py-3 px-6">{{ $produkItem->stok }}</td>
                            <td class="py-3 px-6">{{ $produkItem->kategori->nama_kategori}}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('edit-produk', ['id' => $produkItem->id]) }}" 
                                        class="bg-yellow-300 hover:bg-yellow-500 text-black font-semibold py-1 px-3 rounded-lg transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('hapus-produk', $produkItem->id) }}" method="POST" class="inline-block">
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
</body>
</html>