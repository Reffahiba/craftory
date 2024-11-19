<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign In</title>
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
        <div class="w-full max-w-2xl">
            <div class="bg-light-apricot rounded-2xl overflow-hidden flex shadow-2xl border border-rust">
                <div class="w-1/2 justify-center items-center p-8">
                    <img src="{{ asset('assets/img/login.png') }}" alt="logo-login" class="w-full h-full ml-5">
                </div>
                <div class="w-1/2 justify-center items-center p-8">
                    <div class="flex flex-col justify-center items-center mr-3 mt-5">
                        <img src="{{ asset('assets/img/craftory-word.png') }}" alt="craftory-word" class="mb-3">
                        <h1 class="font-bold text-3xl my-3 text-purple-brown">LOG IN</h1>
                        <form action="{{ route('login-admin-proses') }}" method="POST">
                            @csrf
                            <div class="flex flex-col justify-center items-center">
                                <input type="email" name="email" id="email" placeholder="Email" class="outline-none px-3 py-2 rounded-full my-2">
                                <input type="password" name="password" id="password" placeholder="Password" class="outline-none px-3 py-2 rounded-full my-2">
                                <button type="submit" class="bg-purple-brown text-white text-lg rounded-full my-3 px-8 font-semibold">LOG IN</button>
                            </div>
                        </form>
                        <p class="text-sm text-purple-brown font-bold">Tidak punya akun? <a href="/admin_register" class="text-light-blue">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>