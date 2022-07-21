<!--
MIT License
Copyright (c) 2021-2022 FoxxoSnoot
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
-->

@extends('admin', [
    'pageTitle' => 'Items'
])

@section('header')
    <section class="content-header">
        <h1>
            Items
            <small>({{ $total }} total)</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Items</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Search for item</h3>
        </div>
        <div class="box-body">
            <form method="GET">
                <div class="input-group">
                    <input class="form-control" type="text" name="search" placeholder="ID" value="{{ request()->search }}">
                    <span class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="submit">Search</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    @if (request()->has('search') && request()->search != '' && !$item)
        <div class="box box-primary">
            <div class="box-body">
                <p>No results.</p>
            </div>
        </div>
    @endif
    @if ($item)
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-primary">{{ $item->name }}</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4 col-lg-2 text-center">
                        <img class="img-responsive" src="{{ $item->thumbnail() }}">
                        <br>
                        <a href="{{ route('market.show', ['id' => $item->id]) }}" class="btn btn-fluid btn-primary" target="_blank">View</a>
                        @if ($item->type != 7)
                            <a href="{{ getAsset($item->type, $item->id) }}" class="btn btn-fluid btn-success" target="_blank">{{ ($item->type >= 4) ? 'Template' : 'Texture' }}</a>
                        @endif
                    </div>
                    <div class="col-md-8 col-lg-3">
                        <ul>
                            <li><strong>Item ID:</strong> {{ $item->id }}</li>
                            <li><strong>Creator:</strong> {{ $item->creator->username }} <a href="{{ route('admin.users', ['search' => $item->creator->username]) }}">[Click to view]</a></li>
                            <li><strong>Coins:</strong> {{ $item->price_coins }}</li>
                            <li><strong>Cash:</strong> {{ $item->price_cash }}</li>
                            <li><strong>Is Collectible:</strong> {{ ($item->collectible) ? 'Yes' : 'No' }}</li>
                            <li><strong>Is Hidden:</strong> {{ ($item->public_view) ? 'Yes' : 'No' }}</li>
                            <li><strong>Creation Date:</strong> {{ $item->created_at->format('M d, Y') }}</li>
                            <li><strong>Last Updated:</strong> {{ $item->updated_at->format('M d, Y') }}</li>
                            <li><strong># Owners:</strong> {{ $item->ownerCount() }}</li>
                            @if ($item->collectible)
                                <li><strong># Total Stock:</strong> {{ $item->collectible_stock_original }}</li>
                                <li><strong># Remaining Stock:</strong> {{ $item->collectible_stock }}</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-12 col-lg-7">
                        @if (Auth::user()->power >= 3)
                            <h3 class="text-primary font-weight-bold">Modify</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Render</h4>
                                    <form action="{{ route('admin.items.modify') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="type" value="render">
                                        <button class="btn btn-block btn-primary" type="submit">Update</button>
                                    </form>
                                </div>
                                @if ($item->status != 'accepted')
                                    <div class="col-md-4">
                                        <h4>Accept</h4>
                                        <form action="{{ route('admin.items.modify') }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <input type="hidden" name="type" value="accept">
                                            <button class="btn btn-block btn-primary" type="submit">Update</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endif
                        @if (Auth::user()->power >= 2)
                            <br>
                            <h3 class="text-danger font-weight-bold">Remove</h3>
                            <div class="row">
                                @if ($item->status == 'accepted')
                                    <div class="col-md-4">
                                        <h4>Decline</h4>
                                        <form action="{{ route('admin.items.remove') }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">
                                            <input type="hidden" name="type" value="decline">
                                            <button class="btn btn-block btn-danger" type="submit">Remove</button>
                                        </form>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <h4>Name</h4>
                                    <form action="{{ route('admin.items.remove') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="type" value="name">
                                        <button class="btn btn-block btn-danger" type="submit">Scrub</button>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <h4>Description</h4>
                                    <form action="{{ route('admin.items.remove') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="type" value="description">
                                        <button class="btn btn-block btn-danger" type="submit">Scrub</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
