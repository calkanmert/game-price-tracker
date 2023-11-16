@extends('app.layouts.layout')

@section('page_content')
<div class="row grid gap-3">
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-12 bg-body-tertiary py-3 px-3">
                <img src="{{ $game->image }}" class="img-fluid">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12 bg-body-tertiary py-4 px-4">
                <h4>{{ $game->name }}</h4>
                <p class="mt-3">
                    {{ $game->description }}
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 bg-body-tertiary py-3 px-3 mt-3">
                <iframe width="100%" height="200" src="{{ $game->video }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="row">
            @foreach($game->products as $product)
            <div class="card mb-3 bg-body-tertiary rounded-0 border-0">
                <div class="row g-0">
                    <div class="col-md-1 d-flex justify-content-center align-items-center">
                        <img src="{{ $product->store->image }}" class="img-fluid rounded-start" width="50px">
                    </div>
                    <div class="col-md-11">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">{{ $product->store->name }} <i class="fa fa-check-circle"></i></h6>
                                @foreach($product->prices as $price)
                                    <span class="badge text-bg-secondary">
                                        @money($price->price, $price->currency->code)
                                    </span>
                                @endforeach
                            </div>
                            <div>
                                <a href="{{ $product->url }}" target="_blank" class="btn btn-secondary">
                                    <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@stop
