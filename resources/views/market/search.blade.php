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
    'pageTitle' => 'Market',
    'bodyClass' => 'market-page',
    'gridClass' => 'market-grid'
])

@section('additional_js')
    <script src="{{ asset('js/site/market.js?v=6') }}"></script>
@endsection

@section('content')
    <div class="grid-x grid-margin-x mb-25">
        <div class="auto cell">
            <div class="market-header">Market</div>
        </div>
        <div class="shrink cell">
            <a href="{{ route('creator-area.index') }}" class="button button-green">Create</a>
            <a href="{{ route('market.index') }}" class="button button-blue">Home</a>
        </div>
    </div>
    <div class="grid-x grid-margin-x mb-15">
        <div class="cell small-12 medium-2">
            <select class="form-input" id="category-selector">
                <option value="recent" selected>Recent</option>
                <option value="heads">Heads</option>
                <option value="hats">Hats</option>
                <option value="faces">Faces</option>
                <option value="accessories">Accessories</option>
                <option value="t-shirts">T-Shirts</option>
                <option value="shirts">Shirts</option>
                <option value="pants">Pants</option>
                <option value="sets">Sets</option>
            </select>
        </div>
        <div class="cell small-12 medium-10">
            <div class="push-5 show-for-small-only"></div>
            <input class="form-input" id="search" type="text" placeholder="Search and press enter">
        </div>
    </div>
    @if (!settings('market_purchases_enabled'))
        <div class="alert alert-warning market-purchases-alert">
            Market purchases are temporarily unavailable. Items may be browsed but are unable to be purchased or traded.
        </div>
    @endif
    <div class="market-header" id="header">Recent</div>
    <div class="container">
        <div class="market-search-results" id="results-for" style="display:none;"></div>
        <div id="items"></div>
        <div class="market-load-more" style="display:none;"><a id="load-more">Load more...</a></div>
    </div>
@endsection
