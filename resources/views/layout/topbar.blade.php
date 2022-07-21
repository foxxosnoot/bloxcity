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

@php
    use App\Models\Friend;
    use App\Models\Message;
    use App\Models\Item;
    use App\Models\Report;

    $pendingItems = 0;
    $pendingReports = 0;

    if (Auth::check()) {
        $friendRequestCount = number_format(Friend::where([['receiver_id', '=', Auth::user()->id], ['status', '=', 'pending']])->count());
        $unreadMessageCount = number_format(Message::where([['receiver_id', '=', Auth::user()->id], ['seen', '=', false]])->count());

        if (Auth::user()->power > 1) {
            $pendingItems = Item::where('status', '=', 'pending')->count();
            $pendingReports = Report::where('seen', '=', false)->count();
        }
    }
@endphp

<nav class="topbar">
    <div class="hide-for-large">
        <div class="topbar-sidebar-toggler" id="sidebarToggler" style="margin-left:0;margin-top:3px;padding:0 15px;">
            <i class="fas fa-bars"></i>
        </div>
        <style>
            .topbar-logo-mobile {
                margin-left: 25%;
            }

            @media only screen and (min-width: 40em) {
                .topbar-logo-mobile {
                    margin-left: 40%;
                }
            }
        </style>
        <a href="{{ (Auth::check()) ? route('dashboard') : route('landing') }}" class="topbar-logo topbar-logo-mobile">
            <img src="{{ storage('web-img/icon.png') }}">
        </a>
        <div class="topbar-right">
            @guest
                <a class="topbar-link dropdown" data-toggle="topbar-user-dropdown">
                    <i class="fas fa-caret-down"></i>
                </a>
                <ul class="dropdown-content navbar-user-dropdown" id="topbar-user-dropdown">
                    <li class="dropdown-item"><a href="{{ route('login') }}">Login</a></li>
                    <li class="dropdown-item"><a href="{{ route('register') }}">Register</a></li>
                </ul>
            @else
                <a class="topbar-link dropdown" data-toggle="topbar-user-dropdown-mobile">
                    <img id="topbarAvatarMobile" src="{{ storage(Auth::user()->headshot_url) }}" style="border-radius:50%;width:35px;height:35px;">
                </a>
                <ul class="dropdown-content navbar-user-dropdown" id="topbar-user-dropdown-mobile">
                    <li class="dropdown-item"><a href="{{ route('users.profile', ['username' => Auth::user()->username]) }}">Profile</a></li>
                    <li class="dropdown-item"><a href="{{ route('account.character.index') }}">Character</a></li>
                    <li class="dropdown-item"><a href="{{ route('account.settings.index') }}">Settings</a></li>
                    <li class="dropdown-item"><a href="{{ route('logout') }}">Logout</a></li>
                </ul>
            @endguest
        </div>
    </div>
    <div class="show-for-large">
        <div class="topbar-left">
            <a href="{{ (Auth::check()) ? route('dashboard') : route('landing') }}" class="topbar-logo">
                <img style="width:125px;" src="{{ storage('web-img/logo.png') }}">
            </a>
            <a href="{{ (Auth::check()) ? route('dashboard') : route('landing') }}" class="topbar-link">Home</a>
            <a href="{{ route('games.index') }}" class="topbar-link">Games</a>
            <a href="{{ route('market.index') }}" class="topbar-link">Market</a>
            <a href="{{ route('forum.index') }}" class="topbar-link">Forum</a>
            @auth
                <a href="{{ route('creator-area.index') }}" class="topbar-link">Create</a>
            @endauth
            <a class="topbar-link dropdown" data-toggle="topbar-more-dropdown">
                <i class="fas fa-caret-down"></i>
            </a>
            <style>.navbar-more-dropdown-auth { margin-left: 509px!important; }</style>
            <style>.navbar-more-dropdown { margin-left: 433px!important; }</style>
            <ul class="dropdown-content @auth navbar-more-dropdown-auth @else navbar-more-dropdown @endauth" id="topbar-more-dropdown">
                <li class="dropdown-item"><a href="{{ route('users.index') }}">Users</a></li>
                <li class="dropdown-item"><a href="{{ config('blox.socials.discord') }}">Discord</a></li>
            </ul>
        </div>
        <div class="topbar-right">
            @guest
                <a href="{{ route('login') }}" class="topbar-link">Login</a>
                <a href="{{ route('register') }}" class="topbar-link">Register</a>
            @else
                <a href="{{ route('account.inbox.index') }}" class="topbar-link" title="{{ $unreadMessageCount }} Messages" data-position="bottom" data-tooltip><i class="icon icon-inbox"></i> {{ $unreadMessageCount }}</a>
                <a href="{{ route('account.friends.index') }}" class="topbar-link" title="{{ $friendRequestCount }} Friend Requests" data-position="bottom" data-tooltip><i class="icon icon-friends"></i> {{ $friendRequestCount }}</a>
                <a href="{{ route('account.money.index', ['from' => 'cash']) }}" class="topbar-link" title="{{ number_format(Auth::user()->currency_cash) }} Cash" data-position="bottom" data-tooltip><i class="icon icon-cash"></i> {{ number_format(Auth::user()->currency_cash) }}</a>
                <a href="{{ route('account.money.index', ['from' => 'coins']) }}" class="topbar-link" title="{{ number_format(Auth::user()->currency_coins) }} Coins" data-position="bottom" data-tooltip><i class="icon icon-coins"></i> {{number_format( Auth::user()->currency_coins) }}</a>
                <a class="topbar-link dropdown" data-toggle="topbar-user-dropdown">
                    <img id="topbarAvatar" src="{{ storage(Auth::user()->headshot_url) }}" style="border-radius:50%;width:25px;height:25px;margin-right:5px;">
                    {{ Str::limit(Auth::user()->username, 10, '...') }}
                    <i class="fas fa-caret-down"></i>
                </a>
                <ul class="dropdown-content navbar-user-dropdown" id="topbar-user-dropdown">
                    <li class="dropdown-item"><a href="{{ route('users.profile', ['username' => Auth::user()->username]) }}">Profile</a></li>
                    <li class="dropdown-item"><a href="{{ route('account.character.index') }}">Character</a></li>
                    <li class="dropdown-item"><a href="{{ route('account.settings.index') }}">Settings</a></li>
                    @if (Auth::user()->power > 0)
                    <li class="dropdown-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    @endif
                    <li class="dropdown-item"><a href="{{ route('logout') }}">Logout</a></li>
                </ul>
            @endguest
        </div>
    </div>
</nav>
<div class="topbar-push"></div>
@if ($pendingItems > 0 || $pendingReports > 0)
    <div class="site-banner alert alert-error">
        There are {{ number_format($pendingItems) }} pending items & {{ number_format($pendingReports) }} pending reports.
    </div>
@endif
