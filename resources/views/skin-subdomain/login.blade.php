@extends('skin-subdomain.layouts.auth')
@section('title', 'Login')
	 
@section('content')
<main class="max-w-5xl mx-auto flex flex-col content-center">
    <div class="flex flex-col py-8">
        <img src="{{ asset('img/TreeLogoSmall.png') }}" alt="" class="mx-auto" height="64">
    </div>
    <div class="flex flex-col max-w-sm p-4 bg-gray-50 rounded-lg shadow-2xl dark:bg-gray-800 mx-auto">
        <h1 class="text-xl mb-4">Login with <img src="{{ asset('img/gamejolt-logo-light-1x.png') }}" alt="Gamejolt" class="inline-block"></h1>
        <form method="post" action="{{ route('gj-login-post') }}">
            <div class="text-gray-700">
                <label for="username" class="block mb-1">Username</label>
                <input type="text" id="username" name="username" class="w-full h-10 px-3 mb-2 text-base text-gray-700 placeholder-gray-600 border rounded-lg focus:shadow-outline" placeholder="Username" required="" autofocus="">
            </div>
            <div class="text-gray-700">
                <label for="token" class="block mb-1">Game Token</label>
                <input type="password" id="token" name="token" class="w-full h-10 px-3 mb-2 text-base text-gray-700 placeholder-gray-600 border rounded-lg focus:shadow-outline" placeholder="Game Token" required="">
            </div>
            <p class="text-xs mb-4"><a href="https://gamejolt.com/help/tokens" class="link-secondary text-decoration-none" target="_blank"><i class="far fa-question-circle"></i> What's my game token?</a></p>
            <div class="flex w-full">
                <button class="py-2 px-4 bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:ring-offset-purple-200 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg " type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Login
                </button>
            </div>
            @csrf
        </form>
    </div>
    <p class="flex flex-1 mt-3 text-xs mx-auto text-center">
        <a class="text-white" href="https://pokemon3d.net">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
              </svg>
            Go back to pokemon3d.net
        </a>
    </p>
</main>
@endsection