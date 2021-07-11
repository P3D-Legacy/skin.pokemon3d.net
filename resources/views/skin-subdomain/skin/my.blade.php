@extends('skin-subdomain.layouts.main')
@section('title', 'My Skins')
     
@section('content')
<h2 class="text-3xl font-extrabold leading-9 border-b-2 border-gray-100 text-gray-50 mb-4 mt-4 pb-1">
    My Skins &mdash; {{ \App\Models\GJUser::find(session()->get('gjid'))->skins()->count() }} / {{ env('SKIN_MAX_UPLOAD') }}
</h2>
<div class="gap-4 grid grid-cols-1 grid-flow-row auto-rows-max sm:grid-cols-2 lg:grid-cols-3">
    @foreach($skins as $skin)
        @include('skin.component.card', ['skin' => $skin])
    @endforeach
</div>

@endsection