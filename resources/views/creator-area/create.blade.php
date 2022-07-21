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

@extends($layout, [
    'pageTitle' => 'Create New ' . ucfirst($type),
    'gridFluid' => true
])

@section('additional_js')
    <script>
        $(function() {
            $('select[name="onsale"]').change(function() {
                if (this.value == 1) {
                    $('#sale-options').show();
                    $('input[name="price_coins"]').val('0');
                    $('input[name="price_cash"]').val('0');
                } else {
                    $('#sale-options').hide();
                    $('input[name="price_coins"]').val('0');
                    $('input[name="price_cash"]').val('0');
                }
            });

            @if ($type == 'hat' || $type == 'face' || $type == 'accessory' || $type == 'head')
                $('select[name="collectible"]').change(function() {
                    if (this.value == 1) {
                        $('#collectible-options').show();
                        $('input[name="collectible_stock"]').val('');
                    } else {
                        $('#collectible-options').hide();
                        $('input[name="collectible_stock"]').val('0');
                    }
                });
            @endif
        });

        @if ($type == 'face')
            function faceTemplate()
            {
                window.open('{{ storage("awesamrendererbro2/face_template.png") }}');
            }
        @endif
    </script>
@endsection

@section('content')
    @if ($type == 't-shirt' || $type == 'shirt' || $type == 'pants')
        <div class="grid-x grid-margin-x">
            <div class="cell small-12 medium-6 @if ($type != 'shirt' && $type != 'pants') medium-offset-3 @endif">
                <div class="container">
                    <h5>Create New {{ ($type == 't-shirt') ? 'T-Shirt' : ucfirst($type) }}</h5>
                    <hr>
                    <form action="{{ route('creator-area.store') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="{{ $type }}">
                        <label class="form-label">Name</label>
                        <input class="form-input" type="text" name="name" placeholder="Name" value="{{ request()->input('name') }}" required>
                        <label class="form-label">Description</label>
                        <textarea class="form-input" name="description" rows="5">{{ request()->input('description') }}</textarea>
                        <label class="form-label">Onsale</label>
                        <select class="form-input" name="onsale">
                            <option value="0" selected>No</option>
                            <option value="1">Yes</option>
                        </select>
                        <div id="sale-options" style="display:none;">
                            <div class="grid-x grid-margin-x mb-15">
                                <div class="cell auto">
                                    <label class="form-label">Coins</label>
                                    <input class="form-input" type="number" name="price_coins" placeholder="Price" value="0" required>
                                </div>
                                <div class="cell auto">
                                    <label class="form-label">Cash</label>
                                    <input class="form-input" type="number" name="price_cash" placeholder="Price" value="0" required>
                                </div>
                            </div>
                        </div>
                        <label class="form-label">Template</label>
                        <input name="image" type="file">
                        <button class="button button-blue" type="submit">Create</button>
                    </form>
                </div>
                <div class="push-25 show-for-small-only"></div>
            </div>
            @if ($type != 't-shirt')
                <div class="cell small-12 medium-6">
                    <div class="container">
                        <h5>Template</h5>
                        <hr>
                        <a href="{{ storage($template) }}" target="_blank">
                            <img src="{{ storage($template) }}" style="width:100%;">
                        </a>
                        <p style="color:red;"><br>Roblox templates are not compatible on {{ config('app.name') }}.</p>
                    </div>
                </div>
            @endif
        </div>
    @else
        @section('header')
            <section class="content-header">
                <h1>
                    Create Item
                    <small>Creative</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Create Item</li>
                </ol>
            </section>
        @endsection

        @section('additional_js')
            @if ($type == 'set')
                <script src="{{ asset('js/site/create_set.js') }}"></script>
            @endif
        @endsection

        <div class="row">
            <div class="col-md-8">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create New {{ ucfirst($type) }}</h3>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('creator-area.store') }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="type" value="{{ $type }}">
                            <label class="form-label">Name</label>
                            <input class="form-control" type="text" name="name" placeholder="Name" value="{{ request()->input('name') }}" required>
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="5">{{ request()->input('description') }}</textarea>
                            <label class="form-label">Onsale</label>
                            <select class="form-control" name="onsale">
                                <option value="0" selected>No</option>
                                <option value="1">Yes</option>
                            </select>
                            <div id="sale-options" style="display:none;">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label class="form-label">Coins</label>
                                        <input class="form-control" type="number" name="price_coins" placeholder="Price" value="0" required>
                                    </div>
                                    <div class="col-xs-6">
                                        <label class="form-label">Cash</label>
                                        <input class="form-control" type="number" name="price_cash" placeholder="Price" value="0" required>
                                    </div>
                                </div>
                            </div>
                            <label class="form-label">Public View</label>
                            <select class="form-control" name="public_view">
                                <option value="0">No</option>
                                <option value="1" selected>Yes</option>
                            </select>
                            <label class="form-label">Official</label>
                            <select class="form-control" name="official">
                                <option value="0">No</option>
                                <option value="1" selected>Yes</option>
                            </select>
                            <label class="form-label">Collectible</label>
                            <select class="form-control" name="collectible">
                                <option value="0" selected>No</option>
                                <option value="1">Yes</option>
                            </select>
                            <div id="collectible-options" style="display:none;">
                                <label class="form-label">Stock</label>
                                <div class="grid-x grid-margin-x mb-15">
                                    <div class="cell auto">
                                        <input class="form-control" type="number" name="collectible_stock" placeholder="Stock" value="0" required>
                                    </div>
                                </div>
                            </div>
                            @if ($type != 'set')
                                @if ($type != 'head')
                                    <label class="form-label">Texture</label>
                                    <input name="image" type="file">
                                @endif
                                @if ($type != 'face')
                                    <label class="form-label">Model</label>
                                    <input name="model" type="file">
                                @endif
                            @else
                                <label class="form-label">Items</label>
                                <input class="form-control" type="text" name="items" placeholder="Enter IDs (Example: 12 75 58)" required>
                            @endif
                            <button class="btn btn-success" type="submit">Create</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tips</h3>
                    </div>
                    <div class="box-body">
                        <ul>
                            @foreach ($tips as $tip)
                                <li>{!! $tip !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
