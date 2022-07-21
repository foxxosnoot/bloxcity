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
    'pageTitle' => 'Users',
    'bodyClass' => 'users-page'
])

@section('content')
    <div class="container">
        <div class="grid-x grid-margin-x mb-25">
            <div class="auto cell">
                <div class="search-header">Search Users</div>
            </div>
            <div class="shrink cell">
                <p>{{ number_format($totalUsers) }} Total Users</p>
            </div>
        </div>
        <form action="{{ route('users.index') }}" method="GET">
            <input class="form-input" type="text" name="search" placeholder="Search and press enter">
        </form>
    </div>
    <div class="push-15"></div>
    <div id="users">
        @forelse ($users as $user)
            <div class="container user-container">
                <div class="grid-x grid-margin-x align-middle">
                    <div class="cell small-3 medium-2 text-center">
                        <a href="{{ route('users.profile', ['username' => $user->username]) }}">
                            <div class="user-avatar">
                                <img class="user-avatar-image" src="{{ storage($user->headshot_url) }}">
                            </div>
                        </a>
                        <a href="{{ route('users.profile', ['username' => $user->username]) }}" class="user-username">{{ $user->username }}</a>
                    </div>
                    <div class="cell small-7 medium-9">
                        @if ($user->power < 4)
                            <div class="user-description">{{ $user->description ?? 'This user has no description.' }}</div>
                        @else
                            <div class="user-description">{!! $user->description ?? 'This user has no description.' !!}</div>
                        @endif
                    </div>
                    <div class="cell small-2 medium-1 text-right">
                        @if ($user->online())
                            <div style="color:green;">Online</div>
                        @else
                            <div style="color:red;">Offline</div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="container">
                <p>No users found.</p>
            </div>
        @endforelse
    </div>
    {{ $users->onEachSide(1)->links('pagination.default') }}
@endsection
