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

    {{-- <!-- Breadcrumb -->
    <div class="p-4">
        <p class="text-sm text-gray-500">Beranda > Keranjang</p>
    </div> --}}

    <!-- Keranjang Section -->
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Keranjang</h1>
        <div class="flex space-x-8">

            <!-- Produk -->
            <div class="w-3/4">
                <table class="w-full border-collapse border">
                    @if($pesanan && $pesanan->item_pesanan->isNotEmpty())
                        <thead>
                            <tr class="bg-light-apricot text-white">
                                <th class="p-4 text-left text-rust">Produk</th>
                                <th class="p-4 text-left text-rust">Harga</th>
                                <th class="p-4 text-left text-rust">Jumlah</th>
                                <th class="p-4 text-left text-rust">Subtotal</th>
                                <th class="p-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->item_pesanan as $item)
                                <tr class="border-b">
                                    <td class="p-4 flex items-center space-x-4">
                                        <img src="{{ asset($item->produk->foto_produk) }}" alt="{{ $item->produk->nama_produk }}" class="w-16 h-16">
                                        <span>{{ $item->produk->nama_produk }}</span>
                                    </td>
                                    <td class="p-4">Rp{{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                                    <td class="p-4">
                                        <div class="flex items-center space-x-2">
                                            <form action="{{ route('update-keranjang', [$item->id, 'kurang']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-gray-300 px-2 py-1">-</button>
                                            </form>
                                            <p class="text-xl">{{ $item->kuantitas }}</p>
                                            <form action="{{ route('update-keranjang', [$item->id, 'tambah']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-gray-300 px-2 py-1">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="p-4">Rp{{ number_format($item->sub_total, 0, ',', '.') }}</td>
                                    <td class="p-4">
                                        <form action="{{ route('hapus-keranjang', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-red-500" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                                <img src="{{ asset('assets/img/trashcan.png') }}" alt="Delete" class="w-6 h-6">
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                                <tr>
                                    <td class="py-6 text-center" colspan="5">
                                        <button class="bg-purple-brown rounded-lg"><a href="{{ route('dashboard-pembeli') }}" class="text-white text-center p-3 font-medium">Tambah</a></button>
                                    </td>
                                </tr>
                        </tbody>
                    @else
                        <thead>
                            <th class="bg-light-apricot text-black">Keranjang Kosong</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-6 text-center">
                                    <button class="bg-purple-brown rounded-lg"><a href="{{ route('dashboard-pembeli') }}" class="text-white text-center p-3 font-medium">Tambah</a></button>
                                </td>
                            </tr>
                        </tbody>
                    @endif
                </table>
            </div>

            <!-- Total Keranjang -->
            <div class="w-1/4 bg-rust text-white p-6 rounded-lg h-full max-h-[500px] overflow-y-auto">
                @if($pesanan && $pesanan->item_pesanan->isNotEmpty())
                    <form action="{{ route('checkOut', ['id' => $pesanan->id]) }}" method="POST">
                        @csrf
                        <h2 class="text-lg font-bold mb-4">Total Keranjang</h2>
                        <div class="flex justify-between mt-2">
                            <span>Total Harga</span>
                            <span class="font-bold text-lg">Rp{{ number_format($pesanan->item_pesanan->sum('sub_total'), 0, ',', '.') }}</span>
                        </div>
                        <button type="submit" class="w-full bg-white text-purple-brown font-medium mt-4 py-2 rounded">Check Out</button>
                    </form>
                @else
                    <h2 class="text-lg font-bold mb-4">Total Keranjang</h2>
                    <div class="flex justify-between mt-2">
                        <span>Total Harga</span>
                        <span class="font-bold text-lg">Rp0</span>
                    </div>
                @endif    
            </div>
        </div>
    </div>
</body>


</html>
