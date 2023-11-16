@extends('app.layouts.layout')

@section('page_content')
<div class="row">
    <div class="col-md-12">
        @foreach ($games as $game)
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3 bg-body-tertiary">
                    <div class="row g-0">
                        <div class="col-md-2">
                            <a href="{{ url('products', $game->id) }}">
                                <img src="{{ $game->image }}" class="img-fluid rounded-start" style="width:250px">
                            </a>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <a class="text-decoration-none text-light" href="{{ url('products', $game->id) }}">
                                    <h5 class="card-title">{{ $game->name }}</h5>
                                </a>
                                @foreach ($game->products as $product)
                                <span class="badge @if ($product->store->offical) text-bg-primary @else text-bg-secondary @endif">{{ $product->store->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        @endforeach 
    </div>
</div>
@stop
