@extends('skin-subdomain.layouts.main')
@section('title', 'Home')
     
@section('content')
<div class="grid grid-flow-col grid-cols-3 gap-4">
    <div class="">
        <div class="overflow-hidden shadow-lg rounded-lg h-90 m-auto w-full">
            <div class="w-full block h-full">
                <div class="bg-white dark:bg-gray-800 w-full p-4">
                    <div class="text-gray-800 dark:text-white text-xl font-medium mb-2">Your Current Skin </div>
                    <div class="card-body">
                        @if(File::exists(public_path('player/'.$id.'.png')))
                            <img src="{{ asset('player/'.$id.'.png') }}?r={{ now()->timestamp }}" class="mx-auto my-4">
                        @else
                            <p>No skins have been added to your account yet.</p>
                            @if(\App\Models\GJUser::find(session()->get('gjid'))->skins()->count() >= env('SKIN_MAX_UPLOAD'))
                                <div class="px-4 py-3 mt-4 leading-normal text-blue-800 bg-blue-300 rounded-lg text-sm">
                                    <i class="fas fa-info-circle mr-2" aria-hidden="true"></i> Your slots are full. You cannot import from the old site unless you delete one of the slots.
                                </div>
                            @else
                                <p class="text-sm mt-3">
                                    <a href="{{ route('import', $id) }}" class="w-full inline-block items-center py-2 px-4 mt-2 text-blue-900 transition-colors duration-150 bg-blue-200 rounded-lg focus:shadow-outline hover:bg-blue-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Check for skin to import from the old site
                                    </a>
                                </p>
                            @endif
                        @endif
                    </div>
                    @if(File::exists(public_path('player/'.$id.'.png')))
                        <div class="mt-2">
                            <a class="inline-flex items-center h-8 px-4 text-sm text-blue-100 transition-colors duration-150 bg-blue-700 rounded-lg focus:shadow-outline hover:bg-blue-800" href="{{ route('player-skin-duplicate') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2h2m3-4H9a2 2 0 00-2 2v7a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-1m-1 4l-3 3m0 0l-3-3m3 3V3" />
                                </svg>
                                Save to "My skins"
                            </a>
                            @if(File::exists(public_path('player/'.$id.'.png')))
                                <a class="inline-flex items-center h-8 px-4 ml-1 text-sm text-red-100 transition-colors duration-150 bg-red-700 rounded-lg focus:shadow-outline hover:bg-red-800" href="{{ route('player-skin-destroy') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="overflow-hidden shadow-lg rounded-lg h-90 m-auto w-full mt-4">
            <div class="w-full block h-full">
                <div class="bg-white dark:bg-gray-800 w-full p-4">
                    <div class="text-gray-800 dark:text-white text-xl font-medium mb-2">Admin Skin Deletion Activity</div>
                    <div class="text-sm">
                        @if($activity->count())
                            @foreach ($activity as $log)
                                <p class="m-0"><span class="text-gray-500">{{ $log->created_at }}:</span> {{ $log->properties['reason'] }}</p>
                            @endforeach
                        @else
                            <p class="m-0">Nothing found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-span-2">
        <div class="overflow-hidden shadow-lg rounded-lg h-90 m-auto w-full">
            <div class="w-full block h-full">
                <div class="bg-white dark:bg-gray-800 w-full p-4">
                    <div class="text-gray-800 dark:text-white text-xl font-medium mb-2">Skin Information</div>
                    <div class="text-gray-800 dark:text-gray-300 font-light text-md">
                        <p>Want to make your own skin? <a href="{{ asset('img/template.png') }}" class="text-green-500">Download this template</a> to get started.</p>
                        <h6 class="text-gray-700 dark:text-gray-200 font-bold mt-1">File Validation</h6>
                        <ul class="list-disc pl-6">
                            <li>Less than 2MB</li>
                            <li>Has to be a PNG-file</li>
                            <li>Dimensions ratio of 3/4</li>
                        </ul>
                        <h6 class="text-gray-700 dark:text-gray-200 font-bold mt-1">Rules</h6>
                        <ul class="list-disc pl-6">
                            <li>Every part (for a 96x128 sprite, every 32x32 portion) of the skin has to contain at least one pixel that is not transparent.</li>
                            <li>You have to own the rights to use the image you upload.</li>
                            <li>The image must not contain any sexual or harassing content.</li>
                        </ul>
                        <p class="mt-2 text-sm text-gray-500">If all of the above rules apply to your skin and you upload it, you transfer all rights to the P3D Team. We can alter and delete your skin as long as it stays on our servers.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="overflow-hidden shadow-lg rounded-lg h-90 w-full m-auto">
            <div class="w-full block h-full">
                <div class="bg-white dark:bg-gray-800 w-full p-4">
                    <p class="text-gray-800 dark:text-white text-xl font-medium mb-2">
                        <img src="{{ asset('img/gamejolt-logo-light-1x.png') }}" class="inline-block"> Account
                    </p>
                    <p class="text-gray-800 dark:text-gray-300 font-light text-md">
                        <img src="{{ $avatar_url ?? '' }}" class="rounded-full h-16 w-16">
                        Type: {{ $type ?? '' }}<br>
                        Signed up: {{ $signed_up ?? '' }}<br>
                        Last logged in: {{ $last_logged_in ?? '' }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="overflow-hidden shadow-lg rounded-lg h-90 w-full m-auto mt-4">
            <div class="w-full block h-full">
                <div class="bg-white dark:bg-gray-800 w-full p-4">
                    <p class="text-gray-800 dark:text-white text-xl font-medium mb-2">
                        Game Information
                    </p>
                    <p class="text-gray-800 dark:text-gray-300 font-light text-md">
                        The latest game release is <span class="text-green-600">{{ GitHubHelper::getVersion() }}</span> and was released <span class="text-blue-500">{{ \Carbon\Carbon::parse(GitHubHelper::getReleaseDate())->diffForHumans() }}</span>.
                    </p>
                    <a href="{{ GitHubHelper::getDownloadUrl() }}" class="w-full inline-block items-center py-2 px-4 mt-2 text-green-50 transition-colors duration-150 bg-green-700 rounded-lg focus:shadow-outline hover:bg-green-800 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        Download latest release
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection