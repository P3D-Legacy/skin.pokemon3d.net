@extends('skin-subdomain.layouts.main')
@section('title', 'Edit Skin')
     
@section('content')
<div class="container mx-auto max-w-md">
    <div class="overflow-hidden rounded-xl shadow-lg bg-white mt-4 md:mt-8 md:mb-4">
        <div class="px-6 py-4">
            <h4 class="mb-3 text-xl font-semibold tracking-tight text-gray-800">Edit Skin</h4>
            <form role="form" action="{{ route('skin-update', $skin->uuid) }}" method="post">
                <div class="mb-3">
                    <label for="formName" class="text-gray-700">Name</label>
                    <input class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="text" id="formName" name="name" value="{{ old('name') ? old('name') : $skin->name }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <input class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-offset-0 focus:ring-green-200 focus:ring-opacity-50" type="checkbox" id="checkPublic" name="public" @if (old('public') ?? $skin->public) {{'checked'}} @endif>
                    <label class="text-gray-700" for="checkPublic">Public <span class="text-gray-500 text-sm ml-2">Other users will be able to see this skin</span></label>
                    @error('public')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @csrf
                <button type="submit" class="py-2 px-4 flex justify-center items-center bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:ring-offset-red-200 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    Update
                </button>
            </form>
        </div>
    </div>
</div>

@endsection