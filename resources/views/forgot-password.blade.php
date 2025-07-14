@extends('layouts.app')

@section('title', 'Lupa Password')

@section('heading', 'Lupa Password?')

@section('subheading')
    Masukkan email Anda dan kami akan mengirim link reset password
@endsection

@section('content')
    <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Email Address
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-300 @enderror"
                    placeholder="Masukkan email yang terdaftar">
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
                Kirim Link Reset Password
            </button>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                Kembali ke halaman login
            </a>
        </div>
    </form>
@endsection
