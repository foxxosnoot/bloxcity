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
    'bodyClass' => 'market-page market-browse-page',
    'gridClass' => 'market-grid'
])

@section('additional_css')
    <link rel="stylesheet" href="https://unpkg.com/swiper@6.8.4/swiper-bundle.min.css">
@endsection

@section('additional_js')
    <script src="https://unpkg.com/swiper@6.8.4/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
                direction: 'horizontal',
                slidesPerView: 2,
                loop: false,
                pagination: { el: '.swiper-pagination', },
                navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev', },
                breakpoints: {
                    640: {
                        slidesPerView: 5
                    }
                }
        });
    </script>
@endsection

@section('content')
    @if (!settings('market_purchases_enabled'))
        <div class="alert alert-warning market-purchases-alert">
            Market purchases are temporarily unavailable. Items may be browsed but are unable to be purchased or traded.
        </div>
    @endif
    <div class="grid-x grid-margin-x mb-25">
        <div class="auto cell">
            <div class="market-header">Market</div>
        </div>
        <div class="shrink cell">
            <a href="{{ route('creator-area.index') }}" class="button button-green">Create</a>
            <a href="{{ route('market.search') }}" class="button button-blue">Search</a>
        </div>
    </div>
    <div class="market-categories">
        @forelse (config('blox.market_categories') as $category)
            @if ($category['visible'])
                <div class="market-category" id="category_{{ $category['id'] }}">
                    <div class="market-header">{{ $category['title'] }}</div>
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            @forelse ($categoryQueries[$category['id']] as $item)
                                <div class="swiper-slide market-item-cell">
                                    <div class="container">
                                        <a href="{{ route('market.show', ['id' => $item->id]) }}" title="{{ $item->name }}">
                                            <img class="market-item-thumbnail" src="{{ $item->thumbnail() }}">
                                        </a>
                                        <a href="{{ route('market.show', ['id' => $item->id]) }}" class="market-item-name" title="{{ $item->name }}">{{ $item->name }}</a>
                                        <div class="market-item-creator">Creator: <a href="{{ route('users.profile', ['username' => $item->creator->id]) }}">{{ $item->creator->username }}</a></div>
                                        <strong>
                                            @if ($item->onsale)
                                                <div class="market-item-price text-center">
                                                    @if ($item->price_coins > 0)
                                                        <div class="market-item-price-coins">
                                                            <i class="icon icon-coins"></i> {{ number_format($item->price_coins) }}
                                                        </div>
                                                    @endif
                                                    @if ($item->price_cash > 0)
                                                        <div class="market-item-price-cash">
                                                            <i class="icon icon-cash"></i> {{ number_format($item->price_cash) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="market-item-price text-center" style="opacity:.6;">Offsale</div>
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            @empty
                                <style>#swiper_prev_{{ $category['id'] }}, #swiper_next_{{ $category['id'] }} { display: none; }</style>
                                <p>No items found.</p>
                            @endforelse
                        </div>
                        <div class="swiper-button-prev" id="swiper_prev_{{ $category['id'] }}"></div>
                        <div class="swiper-button-next" id="swiper_next_{{ $category['id'] }}"></div>
                    </div>
                </div>
            @endif
        @empty
            <p>There are currently no categories.</p>
        @endforelse
    </div>
@endsection
