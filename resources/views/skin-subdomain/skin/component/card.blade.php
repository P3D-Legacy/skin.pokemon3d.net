
<div class="flex max-w-md bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="w-1/4 items-center justify-center pt-4 pl-4">
        <img class="mx-auto" src="{{ Storage::disk('skin')->exists($skin->path()) ? asset('skin/'.$skin->path()) : asset('img/noskin.png') }}" height="128" width="96">
    </div>
    <div class="w-3/4 p-4">
        <h1 class="text-gray-900 font-bold text-2xl break-all">
            <a href="{{ route('skin-show', $skin->uuid) }}">{{ $skin->name }}</a>
        </h1>
        <p class="mt-2 text-gray-600 text-xs">
            @if(session()->get('gjid') == $skin->owner_id)
                Public: {{ ($skin->public) ? 'Yes' : 'No' }}<br>
            @endif
            Owned by: <a class="text-green-800" href="{{ route('user-show', $skin->owner_id) }}">{{ $skin->user->gju ?? $skin->owner_id }}</a><br>
            Uploaded: {{ $skin->created_at->diffForHumans() }}<br>
            File size: {{ Storage::disk('skin')->exists($skin->path()) ? \ByteUnits\Binary::bytes(Storage::disk('skin')->size($skin->path()))->format() : 'N/A' }}
        </p>
        <div class="flex item-center mt-2 text-sm">
            <p>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                {{ $skin->likers()->count() }} likes
            </p>
        </div>
        <div class="flex item-center justify-between mt-3">
            @if(request()->is('uploaded/skins*') || request()->is('player/skins'))
                <form class="w-full" method="post" action="{{ route('uploaded-skin-destroy', $skin->uuid) }}">
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
            @else
                @if(!request()->is('skins/public/*'))
                    <a class="px-2 py-1 bg-blue-800 text-blue-50 text-xs font-bold uppercase rounded" href="{{ route('skin-show', $skin->uuid) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Show
                    </a>
                @endif
                @if(session()->get('gjid') != $skin->owner_id)
                    @if($skin->isLikedBy(\App\Models\GJUser::find(session()->get('gjid'))))
                        <a class="px-2 py-1 bg-red-800 text-red-50 text-xs font-bold uppercase rounded" href="{{ route('skin-like', $skin->uuid) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Liked
                        </a>
                    @else
                        <a class="px-2 py-1 bg-red-600 text-red-50 text-xs font-bold uppercase rounded" href="{{ route('skin-like', $skin->uuid) }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Like
                        </a>
                    @endif
                @else
                    <a class="px-2 py-1 bg-yellow-600 text-yellow-50 text-xs font-bold uppercase rounded" href="{{ route('skin-edit', $skin->uuid) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </a>
                    <a class="px-2 py-1 bg-red-700 text-red-50 text-xs font-bold uppercase rounded" href="{{ route('skin-destroy', $skin->uuid) }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </a>
                @endif
                <a class="px-2 py-1 bg-gray-800 text-gray-50 text-xs font-bold uppercase rounded" href="{{ route('skin-apply', $skin->uuid) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11" />
                    </svg>
                    Apply
                </a>
            @endif
        </div>
    </div>
</div>