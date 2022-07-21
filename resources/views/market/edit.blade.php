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

@extends('master', [
    'pageTitle' => 'Edit '. $item->name,
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

            @if ($item->type == 1 || $item->type == 2 || $item->type == 3 || $item->type == 7 || $item->type == 8)
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
    </script>
@endsection

@section('content')
    <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-6">
            <div class="container">
                <h5>Edit {{ $item->name }}</h5>
                <hr>
                <form action="{{ route('market.edit.update') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $item->id }}">
                    <label class="form-label">Name</label>
                    <input class="form-input" type="text" name="name" placeholder="Name" value="{{ $item->name }}" required>
                    <label class="form-label">Description</label>
                    <textarea class="form-input" name="description" rows="5">{{ $item->description }}</textarea>
                    <label class="form-label">Onsale</label>
                    <select class="form-input" name="onsale">
                        <option value="0" @if (!$item->onsale) selected @endif>No</option>
                        <option value="1" @if ($item->onsale) selected @endif>Yes</option>
                    </select>
                    <div id="sale-options" @if (!$item->onsale) style="display:none;" @endif>
                        <div class="grid-x grid-margin-x mb-15">
                            <div class="cell auto">
                                <label class="form-label">Coins</label>
                                <input class="form-input" type="number" name="price_coins" placeholder="Price" value="{{ $item->price_coins }}" required>
                            </div>
                            <div class="cell auto">
                                <label class="form-label">Cash</label>
                                <input class="form-input" type="number" name="price_cash" placeholder="Price" value="{{ $item->price_cash }}" required>
                            </div>
                        </div>
                    </div>
                    @if ((Auth::user()->power == 1 || Auth::user()->power > 2) && ($item->type == 4 || $item->type == 5 || $item->type == 6))
                        <label class="form-label">Texture</label>
                        <input name="image" type="file">
                    @endif
                    @if ($item->type == 1 || $item->type == 2 || $item->type == 3 || $item->type == 7 || $item->type == 8)
                        <label class="form-label">Public View</label>
                        <select class="form-input" name="public_view">
                            <option value="0" @if (!$item->public_view) selected @endif>No</option>
                            <option value="1" @if ($item->public_view) selected @endif>Yes</option>
                        </select>
                        <label class="form-label">Collectible</label>
                        <select class="form-input" name="collectible">
                            <option value="0" @if (!$item->collectible) selected @endif>No</option>
                            <option value="1" @if ($item->collectible) selected @endif>Yes</option>
                        </select>
                        @if ($item->type != 8)
                            @if ($item->type != 7)
                                <label class="form-label">Texture</label>
                                <input name="image" type="file">
                            @endif
                            @if ($item->type != 2)
                                <label class="form-label">Model</label>
                                <input name="model" type="file">
                            @endif
                        @else
                            <label class="form-label">Items</label>
                            <input class="form-input" type="text" name="items" placeholder="Enter IDs (Example: 12 75 58)" required>
                        @endif
                    @endif
                    <button class="button button-blue" type="submit">Edit</button>
                </form>
            </div>
            <div class="push-25 show-for-small-only"></div>
        </div>
        <div class="cell small-12 medium-6">
            <div class="container">
                <h5>Thumbnail</h5>
                <hr>
                <div class="text-center">
                    <img src="{{ $item->thumbnail() }}" style="max-width:100%;">
                </div>
            </div>
        </div>
    </div>
@endsection
