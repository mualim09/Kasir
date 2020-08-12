@extends('adminlte::page')

@section('content')
<div class="row">
    @foreach ($cards as $card)
        <v-info
            title="{{ $card['name'] }}"
            icon="{{ $card['icon'] }}"
            total="{{ $card['total'] }}"
            color="{{ $card['bg_color'] }}"
            ></v-info>
    @endforeach
</div>
@endsection
