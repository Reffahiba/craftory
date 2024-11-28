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
                    <img src="{{ asset('assets/img/login.png') }}" alt="logo-login" class="w-full h-full ml-5">
                </div>
                <div class="w-1/2 justify-center items-center p-8">
                    <div class="flex flex-col justify-center items-center mr-3 mt-5">
                        <img src="{{ asset('assets/img/craftory-word.png') }}" alt="craftory-word" class="mb-3">
                        <h1 class="font-bold text-3xl my-3 text-purple-brown">REGISTER</h1>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-600">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register-proses') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex flex-col justify-center items-center">
                                <input type="text" name="nama_user" id="nama_user" placeholder="Nama Lengkap" class="outline-none px-3 py-2 rounded-full my-2">
                                <input type="email" name="email" id="email" placeholder="Email" class="outline-none px-3 py-2 rounded-full my-2">
                                <input type="tel" name="no_telepon" id="no_telepon" placeholder="Nomor Telepon" class="outline-none px-3 py-2 rounded-full my-2">
                                <input type="password" name="password" id="password" placeholder="Password" class="outline-none px-3 py-2 rounded-full my-2">
                                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Re-enter Password" class="outline-none px-3 py-2 rounded-full my-2">
                                <input type="file" name="foto_user" id="foto_user">
                                {{-- <div class="flex flex-col justify-center items-center py-1">
                                    <div class="relative w-64">
                                        <input type="file" name="foto_user" id="foto_user" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="flex items-center justify-between px-3 py-2 bg-white rounded-full border border-gray-300">
                                            <span class="text-gray-500 truncate">Pilih foto Anda</span>
                                            <svg 
                                                class="w-5 h-5 text-gray-400" 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                fill="none" 
                                                viewBox="0 0 24 24" 
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-4.553a2.121 2.121 0 00-3-3L12 7l-4.553-4.553a2.121 2.121 0 00-3 3L9 10m6 0v6a3 3 0 01-3 3h-3a3 3 0 01-3-3v-6m6 0h3a3 3 0 013 3v6a3 3 0 01-3 3H9a3 3 0 01-3-3v-6" />
                                            </svg>
                                        </div>
                                    </div>
                                </div> --}}
                                <select name="role_id" id="role_id" required class="outline-none px-3 py-2 rounded-full my-2">
                                    <option value="" disabled selected>Pilih Role</option>
                                    @foreach ($role as $roleItem )
                                    <option value="{{ $roleItem->id }}">{{ ucwords($roleItem->nama_role) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="bg-purple-brown text-white text-lg rounded-full my-3 px-8 font-semibold">REGISTER</button>
                            </div>
                        </form>
                        <p class="text-sm text-purple-brown font-bold">Sudah punya akun? <a href="/login" class="text-reddish-brown">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>