@extends('skin-subdomain.layouts.main')
@section('title', 'Public Skins: '.$skin->name)
     
@section('content')

<div class="mt-4">
    @include('skin.component.card', ['skin' => $skin])
</div>

@endsection