@extends('skin-subdomain.layouts.main')
@section('title', 'Admin')
     
@section('content')
<div class="container mx-auto max-w-md">
    <div class="overflow-hidden rounded-xl shadow-lg bg-white mt-4 md:mt-8 md:mb-4">
        <div class="px-6 py-4">
            <h4 class="mb-3 text-xl font-semibold tracking-tight text-gray-800">Edit user</h4>
            <form role="form" action="{{ route('user-update', $user->gjid) }}" method="post">
                <div class="mb-3 opacity-50 pointer-events-none">
                    <label for="gjid" class="text-gray-700">Game Jolt ID</label>
                    <input class=" rounded-lg border-transparent flex-1 appearance-none border border-gray-300 w-full py-2 px-4 bg-white text-gray-700 placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="text" id="gjid" value="{{ $user->gjid }}" disabled="">
                </div>
                <div class="mb-3 opacity-50 pointer-events-none">
                    <label for="gju" class="text-gray-700">Game Jolt Username</label>
                    <input class=" rounded-lg border-transparent flex-1 appearance-none border border-gray-300 w-full py-2 px-4 bg-white text-gray-700 placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent" type="text" id="gju" value="{{ $user->gju }}" disabled="">
                </div>
                <div class="mb-3">
                    <label for="is_admin" class="text-gray-700">Is Admin <small class="text-gray-500 text-sm ml-2">0 = False & 1 = True</small></label>
                    <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="number" min="0" max="1" id="is_admin" name="is_admin" value="{{ $user->is_admin }}">
                    @error('is_admin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @csrf
                <button type="submit" class="w-full py-2 px-6 text-green-100 transition-colors duration-150 bg-green-700 rounded-lg focus:shadow-outline hover:bg-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Save
                </button>
            </form>
        </div>
    </div>
</div>

@endsection