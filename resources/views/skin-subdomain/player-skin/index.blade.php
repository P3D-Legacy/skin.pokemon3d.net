@extends('skin-subdomain.layouts.main')
@section('title', 'Player Skins')
     
@section('content')
<h2 class="text-3xl font-extrabold leading-9 border-b-2 border-gray-100 text-gray-50 mb-4 mt-4 pb-1">
    Player Skins
</h2>

<div class="gap-4 grid grid-cols-1 grid-flow-row auto-rows-max sm:grid-cols-2 lg:grid-cols-3">
    @foreach($playerskins as $playerskin)
        <div class="flex max-w-md bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="w-1/4 items-center justify-center pt-4 pl-4">
                <img class="mx-auto" src="{{ asset('player/'.$playerskin) }}" height="128" width="96">
            </div>
            <div class="w-3/4 p-4">
                <div class="card-body">
                    <h1 class="text-gray-900 font-bold text-2xl">
                        {{ $playerskin }}
                    </h1>
                    <p class="mt-2 text-gray-600 text-xs">
                        Owned by: {{ App\Models\GJUser::where('gjid', str_replace('.png', '', $playerskin))->first()->gju ?? 'Game Jolt ID: '.str_replace('.png', '', $playerskin) }}<br>
                        File size: {{ \ByteUnits\Binary::bytes(Storage::disk('player')->size($playerskin))->format() }}
                    </p>
                    <form class="w-full" method="post" action="{{ route('player-skin-destroy-admin', str_replace('.png', '', $playerskin)) }}">
                        @csrf
                        <p class="m-0 text-blue-500 text-xs my-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Users will be able to see the reason for the deletion!
                        </p>
                        <input class="w-full h-8 px-2 text-sm text-gray-700 placeholder-gray-400 border rounded focus:shadow-outline" type="text" name="reason" placeholder="Add a legit reason here">
                        <button class="px-2 py-1 mt-2 bg-red-700 text-red-50 text-xs font-bold uppercase rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection