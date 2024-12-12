<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Toko</title>
    @vite('resources/css/app.css')
    <style>
        body {
            background-image: url('{{ asset('assets/img/background.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>
    <div class="container mx-auto h-screen flex justify-center items-center">
        <div class="w-full max-w-4xl">
            <div class="bg-light-apricot rounded-2xl overflow-hidden flex shadow-2xl border border-rust">
                <div class="w-1/2 justify-center items-center p-8">
                    <img src="{{ asset('assets/img/potter-wheel 1.png') }}" alt="logo-login" class="w-80 h-100 ml-20 mt-5">
                </div>
                <div class="w-1/2 justify-center items-center p-8">
                    <div class="flex flex-col justify-center items-center mr-3 mt-5">
                        <img src="{{ asset('assets/img/craftory-word.png') }}" alt="craftory-word" class="mb-3">
                        <h1 class="font-bold text-3xl my-3 text-purple-brown">Buat Toko</h1>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-600">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register-toko-proses') }}" method="POST">
                            @csrf
                            <div class="flex flex-col justify-center items-center">
                                <input type="text" name="nama_toko" id="nama_toko" placeholder="Nama Toko" class="outline-none px-3 py-2 rounded-full my-2">
                                <input type="text" name="alamat_toko" id="alamat_toko" placeholder="Alamat Toko" class="outline-none px-3 py-2 rounded-full my-2">
                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                <button type="submit" class="bg-purple-brown text-white text-lg rounded-full my-3 px-8 font-semibold">Buat Toko</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>