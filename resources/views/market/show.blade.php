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
    'pageTitle' => $item->name,
    'pageDescription' => $item->name . ' is an item on ' . config('app.name') . ', a free creative gaming platform designed for kids and teenagers. Play today!',
    'pageImage' => $item->thumbnail(),
    'bodyClass' => 'item-page',
    'gridClass' => 'market-item-grid'
])

@section('additional_meta')
    <meta
        name="item-data"
        data-id="{{ $item->id }}"
        data-owns="{{ $owns }}"
        data-onsale="{{ $item->onsale }}"
        data-collectible="{{ $item->collectible }}"
        data-stock-remaining="{{ $item->collectible_stock ?? 0 }}"
        data-price-coins="{{ $item->price_coins }}"
        data-price-cash="{{ $item->price_cash }}"
        data-balance-after-coins="{{ $balanceAfterCoins }}"
        data-balance-after-cash="{{ $balanceAfterCash }}"
    >
@endsection

@section('additional_css')
    <style>
        .tabs .tab {
            width: 50%;
        }
    </style>
@endsection

@section('additional_js')
    <script src="{{ asset('js/site/item.js?v=10') }}"></script>
    <script>
        var currentTab = 'comments';

        $(function() {
            $('.tab-link').click(function(tab) {
                $(`#${currentTab}_tab`).removeClass('active');
                $(`#${tab.target.id}`).addClass('active');

                $(`#${currentTab}`).hide();

                currentTab = tab.target.id.replace('_tab', '');

                $(`#${currentTab}`).show();
            });
        });
    </script>
@endsection

@section('content')
    @if (!$item->public_view)
        <div class="alert alert-error">
            <div class="grid-x grid-margin-x align-middle">
                <div class="cell shrink left">
                    <i class="icon icon-error"></i>
                </div>
                <div class="cell auto text-center">
                    This item is not viewable by the public.
                </div>
                <div class="cell shrink right">
                    <i class="icon icon-error"></i>
                </div>
            </div>
        </div>
    @endif
    <div class="container mb-25">
        <div class="grid-x grid-margin-x">
            <div class="cell small-12 medium-3">
                <img class="item-thumbnail" src="{{ $item->thumbnail() }}">
                @auth
                    @if (Auth::user()->power > 1)
                        <div class="push-15"></div>
                        <div class="text-center">
                            <a href="{{ route('admin.items', ['search' => $item->id]) }}" class="button button-blue" target="_blank">View in Panel</a>
                        </div>
                    @endif
                @endauth
                <div class="push-25 show-for-small-only"></div>
            </div>
            <div class="cell small-12 medium-6">
                <div class="item-name">{{ $item->name }}</div>
                <div class="item-type">{{ $item->type() }}</div>
                <div class="item-description">{!! nl2br(e($item->description)) !!}</div>
                <div class="push-25 show-for-small-only"></div>
            </div>
            <div class="cell small-12 medium-3 text-center">
                @if ($item->onsale && $item->status == 'accepted' && settings('market_purchases_enabled'))
                    <div class="modal market-modal reveal" id="buy-modal" data-reveal>
                        <div class="modal-title" id="buy-modal-title"></div>
                        <div class="modal-content" id="buy-modal-body"></div>
                        <div class="modal-footer" id="buy-modal-footer"></div>
                    </div>
                    @if ($item->price_coins > 0)
                        <button class="button button-block button-coins item-buy-button" data-currency="coins" data-toggle="buy-modal" {{ $buttonDisabled }}>Buy for {{ $item->price_coins }} Coins</button>
                    @endif

                    @if ($item->price_cash > 0)
                        <button class="button button-block button-green item-buy-button" data-currency="cash" data-toggle="buy-modal" {{ $buttonDisabled }}>Buy for {{ $item->price_cash }} Cash</button>
                    @endif
                @endif
                @auth
                    @if ($item->creator_id == Auth::user()->id || Auth::user()->power == 1 || Auth::user()->power > 2)
                        <a href="{{ route('market.edit', ['id' => $item->id]) }}" class="button button-block button-blue item-buy-button">Edit Item</a>
                    @endif
                @endauth
                @if ($item->collectible)
                    @if ($item->collectible_stock <= 0)
                        <div class="item-sold-out">Sold Out</div>
                    @else
                        <div class="item-stock">{{ $item->collectible_stock }} out of {{ $item->collectible_stock_original }} remaining</div>
                    @endif
                @endif
                <div class="item-creator-title">Creator</div>
                <a href="{{ route('users.profile', ['username' => $item->creator->username]) }}">
                    <div class="item-creator-avatar">
                        <img class="item-creator-avatar-image" src="{{ $creatorImage }}">
                    </div>
                </a>
                <a href="{{ route('users.profile', ['username' => $item->creator->username]) }}" class="item-creator-username">{{ $item->creator->username }}</a>
                <form action="{{ route('market.favorite') }}" method="POST" style="display:inline-block;">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $item->id }}">
                    @if (Auth::check() && Auth::user()->hasFavoritedItem($item->id))
                        <button><i class="icon icon-favorited item-favorite"></i></button>
                    @else
                        <button><i class="icon icon-favorite item-favorite"></i></button>
                    @endif
                </form>
                @auth
                    @if ($item->creator->id != 1)
                        <a href="{{ route('report.index', ['type' => 'item', 'id' => $item->id]) }}"><i class="icon icon-report item-report"></i></a>
                    @endif
                @endauth
            </div>
        </div>
        <div class="push-25"></div>
        <div class="grid-x grid-margin-x text-center">
            <div class="cell small-6 medium-3">
                <div class="item-stat-result">{{ $item->created_at->format('M d, Y') }}</div>
                <div class="item-stat-name">Time Created</div>
            </div>
            <div class="cell small-6 medium-3">
                <div class="item-stat-result">{{ $item->updated_at->format('M d, Y') }}</div>
                <div class="item-stat-name">Last Updated</div>
                <div class="push-15 show-for-small-only"></div>
            </div>
            <div class="cell small-6 medium-3">
                <div class="item-stat-result">{{ $item->ownerCount() }}</div>
                <div class="item-stat-name">Owners</div>
            </div>
            <div class="cell small-6 medium-3">
                <div class="item-stat-result">{{ $item->favoriteCount() }}</div>
                <div class="item-stat-name">Favorites</div>
            </div>
        </div>
    </div>
    @if ($item->collectible && $item->collectible_stock <= 0)
        <div class="modal market-modal reveal" id="buy-collectible-modal" data-reveal>
            <div class="modal-title" id="buy-collectible-modal-title"></div>
            <div class="modal-content" id="buy-collectible-modal-body"></div>
            <div class="modal-footer" id="buy-collectible-modal-footer"></div>
        </div>
        @if ($owns)
            <div class="modal market-modal reveal" id="sell-collectible-modal" data-reveal>
                <form action="{{ route('market.resell') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $item->id }}">
                    <div class="modal-title">Sell Collectible</div>
                    <div class="modal-content">
                        <input class="form-input" type="number" name="price" placeholder="Price" required>
                    </div>
                    <div class="modal-footer">
                        <div class="modal-buttons">
                            <button class="modal-button" type="submit">SELL</button>
                            <button class="modal-button" data-close>CANCEL</button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
        {{-- <div class="item-header">Collectible Information</div>
        <div class="container text-center mb-25">
            <div class="item-estimated-value-header">Estimated Value: <span class="text-cash">$14,519 Cash</span></div>
            <i>Chart coming soon!</i>
        </div> --}}
        <div class="grid-x grid-margin-x">
            <div class="auto cell">
                <div class="item-private-sellers-header">Private Sellers</div>
            </div>
            @if ($owns)
                <div class="shrink cell text-right">
                    <div class="button button-blue" style="padding:5px 25px;" data-toggle="sell-collectible-modal">Sell</div>
                    <div class="push-10"></div>
                </div>
            @endif
        </div>
        <div class="container item-private-sellers-container mb-25">
            @forelse ($item->resellers() as $reseller)
                <div class="grid-x grid-margin-x align-middle reseller">
                    <div class="cell small-5 medium-5">
                        <div class="item-private-seller-user-holder">
                            <a href="{{ route('users.profile', ['username' => $reseller->seller->username]) }}">
                                <div class="item-private-seller-avatar">
                                    <img class="item-private-seller-avatar-image" src="{{ storage($reseller->seller->headshot_url) }}">
                                </div>
                            </a>
                            <a href="{{ route('users.profile', ['username' => $reseller->seller->username]) }}" class="item-private-seller-username">{{ $reseller->seller->username }}</a>
                        </div>
                    </div>
                    <div class="cell small-7 medium-7 text-right">
                        @if (Auth::check() && $reseller->seller->id == Auth::user()->id)
                            <form action="#" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <button class="button button-red item-buy-button" type="submit">Selling for {{ number_format($reseller->price) }} Cash</button>
                            </form>
                        @else
                            <button class="button button-green item-buy-button" data-price="{{ $reseller->price }}" data-reseller_id="{{ $reseller->id }}" data-toggle="buy-collectible-modal">Buy for {{ number_format($reseller->price) }} Cash</button>
                        @endif
                    </div>
                </div>
            @empty
                <p>There is currently nobody selling this item.</p>
            @endforelse
        </div>
    @endif
    @if ($item->type == 8)
        <div class="item-header">Items in this Set</div>
        <div class="container set-items mb-25">
            <div class="grid-x grid-margin-x">
                @forelse ($item->setItems() as $setItem)
                    <div class="cell small-6 medium-2">
                        <div class="suggested-item">
                            <a href="{{ route('market.show', ['id' => $setItem->id]) }}" title="{{ $setItem->name }}">
                                <img class="market-item-thumbnail" src="{{ storage($setItem->thumbnail_url) }}">
                            </a>
                            <a href="{{ route('market.show', ['id' => $setItem->id]) }}" class="market-item-name" title="{{ $setItem->name }}">{{ $setItem->name }}</a>
                        </div>
                        <div class="push-15 show-for-small-only"></div>
                    </div>
                @empty
                    <div class="cell auto">There are no suggested items.</div>
                @endforelse
            </div>
        </div>
    @endif
    <div class="tabs">
        <div class="tab">
            <a class="tab-link active" id="comments_tab">Comments</a>
        </div>
        <div class="tab">
            <a class="tab-link" id="suggested_tab">Suggested Items</a>
        </div>
    </div>
    <div class="container" id="comments">
        <form action="{{ route('market.comment') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <textarea class="form-input" name="body" placeholder="Write your comment here." rows="3"></textarea>
            <button class="button button-blue" type="submit">Post</button>
        </form>
        <hr>
        <div class="item-comments">
            @forelse ($item->comments() as $comment)
                <div class="item-comment grid-x grid-margin-x">
                    <div class="cell small-3 medium-2 text-center">
                        <a href="{{ route('users.profile', ['username' => $comment->creator->username]) }}">
                            <img src="{{ storage($comment->creator->avatar_url) }}">
                        </a>
                        <a href="{{ route('users.profile', ['username' => $comment->creator->username]) }}" class="comment-creator">{{ $comment->creator->username }}</a>
                    </div>
                    <div class="cell small-9 medium-10">
                        <div class="comment-time-posted"><i class="icon icon-time-ago"></i> Posted {{ $comment->created_at->diffForHumans() }}</div>
                        @auth
                            @if (Auth::user()->power > 1)
                                <a href="{{ route('market.comment.moderate', ['id' => $comment->id]) }}" class="comment-delete">
                                    <i class="icon icon-delete"></i>
                                </a>
                            @endif
                            @if ($comment->creator->id != 1)
                                <a href="{{ route('report.index', ['id' => $comment->id, 'type' => 'item-comment']) }}" class="comment-report">
                                    <i class="icon icon-report"></i>
                                </a>
                            @endif
                        @endauth
                        <div class="comment-body">{{ $comment->body }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center">
                    <h1><i class="icon icon-sad"></i></h1>
                    <h5>This item has no comments.</h5>
                </div>
            @endforelse
            {{ $item->comments()->onEachSide(1)->links('pagination.default') }}
        </div>
    </div>
    <div class="container" id="suggested" style="display:none;">
        <div class="grid-x grid-margin-x">
            @forelse ($suggestions as $suggestion)
                <div class="cell small-6 medium-3">
                    <div class="suggested-item">
                        <a href="{{ route('market.show', ['id' => $suggestion->id]) }}" title="{{ $suggestion->name }}">
                            <img class="market-item-thumbnail" src="{{ storage($suggestion->thumbnail_url) }}">
                        </a>
                        <a href="{{ route('market.show', ['id' => $suggestion->id]) }}" class="market-item-name" title="{{ $suggestion->name }}">{{ $suggestion->name }}</a>
                        <div class="market-item-creator">Creator: <a href="{{ route('users.profile', ['username' => $suggestion->creator->username]) }}">{{ $suggestion->creator->username }}</a></div>
                        @if ($suggestion->onsale)
                            <div class="market-item-price">
                                @if ($suggestion->price_coins > 0)
                                    <div class="market-item-price-coins">
                                        <i class="icon icon-coins"></i> {{ $suggestion->price_coins }}
                                    </div>
                                @endif
                                @if ($suggestion->price_cash > 0)
                                    <div class="market-item-price-cash">
                                        <i class="icon icon-cash"></i> {{ $suggestion->price_cash }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="push-15 show-for-small-only"></div>
                </div>
            @empty
                <div class="cell auto">There are no suggested items.</div>
            @endforelse
        </div>
    </div>
@endsection
