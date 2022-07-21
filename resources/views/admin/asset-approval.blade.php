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
    'pageTitle' => 'Asset Approval'
])

@section('header')
    <section class="content-header">
        <h1>
            Asset Approval
            <small>({{ $total }} total)</small>
            <small class="text-danger"><strong>Do NOT accept Roblox templates</strong></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Asset Approval</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="row">
        @forelse ($items as $item)
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row pending-asset">
                            <div class="col-md-4 text-center">
                                <img class="img-responsive" src="{{ getAsset($item->type, $item->id) }}">
                                <br>
                                <a href="{{ route('market.show', ['id' => $item->id]) }}" class="btn btn-fluid btn-primary" target="_blank">View</a>
                                <a href="{{ route('admin.items', ['search' => $item->id]) }}" class="btn btn-fluid btn-info">Info</a>
                            </div>
                            <div class="col-md-8">
                                <h3>{{ $item->name }}</h3>
                                <ul>
                                    <li><strong>Item ID:</strong> {{ $item->id }}</li>
                                    <li><strong>Creator:</strong> {{ $item->creator->username }} <a href="{{ route('admin.users', ['search' => $item->creator->username]) }}">[Click to view]</a></li>
                                    <li><strong>Type:</strong> {{ $item->type() }}</li>
                                    <li><strong>Coins:</strong> {{ $item->price_coins }}</li>
                                    <li><strong>Cash:</strong> {{ $item->price_cash }}</li>
                                    <li><strong>Creation Date:</strong> {{ $item->created_at->format('M d, Y') }}</li>
                                </ul>
                                <br>
                                <div class="text-center">
                                    <form action="{{ route('admin.asset_approval.update') }}" method="POST" style="display:inline-block;">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="action" value="accept">
                                        <button class="btn btn-fluid btn-success" type="submit">Accept</button>
                                    </form>
                                    <form action="{{ route('admin.asset_approval.update') }}" method="POST" style="display:inline-block;">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <input type="hidden" name="action" value="decline">
                                        <button class="btn btn-fluid btn-danger" type="submit">Decline</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-xs-12">There are currently no pending assets.</div>
        @endforelse
    </div>
@endsection
