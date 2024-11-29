<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Craftory</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans">
    <header class="flex items-center justify-between bg-white p-4 shadow-md">
        <div class="flex items-center space-x-3">
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


    <div class="h-screen flex">
        <main class="container mx-auto mt-6">
            <!-- Banner -->
            <div class="bg-rust p-6 text-center">
                <h2 class="text-4xl font-bold text-light-apricot">Big Sale</h2>
                <p class="text-xl text-white">Up to 45% Off</p>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('dashboard-pembeli') }}" method="GET" class="flex items-center mb-4 mt-3 ml-3">
                <div class="flex flex-col mr-5">
                    <label for="kategori" class="text-sm font-medium mb-1 ml-2">Kategori</label>
                    {{-- <input type="text" name="kategori" placeholder="Kategori" value="{{ request('kategori') }}" class="border rounded p-2 ml-2 w-full max-w-xs"> --}}
                    <select name="kategori" id="kategori" class="border rounded p-2 ml-2 w-full max-w-xs">
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach ($kategori as $kategoriItem)
                            <option value="{{ $kategoriItem->nama_kategori }}" {{ request('kategori') == $kategoriItem->id ? 'selected' : ''}}>{{ $kategoriItem->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col mr-5">
                    <label for="min_harga" class="text-sm font-medium mb-1 ml-2">Min Harga</label>
                    <input type="number" name="min_harga" placeholder="Min Harga" value="{{ request('min_harga') }}" class="border rounded p-2 ml-2 w-28">
                </div>
                <div class="flex flex-col mr-2">
                    <label for="max_harga" class="text-sm font-medium mb-1 ml-2">Max Harga</label>
                    <input type="number" name="max_harga" placeholder="Max Harga" value="{{ request('max_harga') }}" class="border rounded p-2 ml-2 w-28">
                </div>
                <div class="flex flex-col mt-6">
                    <button type="submit" class="ml-2 bg-green-500 text-white py-2 px-4 rounded hover:bg-green-700">🔍 Cari</button>
                </div>
            </form>

            <!-- Produk Populer -->
            <section class="mt-10">
                <h2 class="text-2xl font-bold mb-4 ml-3">Produk Populer Hari Ini</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 ml-3">
                    @forelse ($produk as $produkItem)
                        <div class="bg-white shadow rounded-lg pl-2 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                            <img src="{{ asset($produkItem->foto_produk) }}" alt="{{ $produkItem->nama_produk }}" class="w-36 h-36 mt-2 ml-1 object-cover">
                            <div class="p-4">
                                <h2 class="text-lg font-bold text-gray-800 truncate">{{ $produkItem->toko->nama_toko }}</h2>
                                <h3 class="text-lg font-semibold text-gray-800 truncate">{{ $produkItem->nama_produk }}</h3>
                                <p class="text-rust font-bold mt-2">Rp{{ number_format($produkItem->harga, 0, ',', '.') }}</p>
                                <p class="text-black mt-1">Stok: {{ $produkItem->stok }}</p>
                                <p class="text-black mt-1 font-bold">Kategori: {{ $produkItem->kategori->nama_kategori }}</p>
                                <form action="{{ route('tambah-keranjang') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $produkItem->id }}">
                                    <input type="hidden" name="jumlah" value="1">
                                    <button type="submit" class="mt-4 w-full bg-purple-brown text-white py-2 px-4 rounded hover:bg-orange-700 transition">
                                        Tambah ke Keranjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-full text-center text-gray-500">Tidak ada produk tersedia saat ini.</p>
                    @endforelse
                </div>
            </section>

            <footer class="bg-rust text-white py-6 mt-10">
                <div class="container mx-auto text-center">
                    <p>&copy; 2024 Craftery. All rights reserved.</p>
                </div>
            </footer>
        </main>
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
