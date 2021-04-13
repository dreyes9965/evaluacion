@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">      
            @if (auth()->user()->is_admin == 0)
                <h1>Bienvenido {{ auth()->user()->name }}</h1>
            @else
                <supplier/>
            @endif
        </div>
    </div>
</div>
@endsection
