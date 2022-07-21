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
    'pageTitle' => 'Games',
    'bodyClass' => 'games-page',
    'gridClass' => 'games-grid'
])

@section('content')
    <div class="games-header">Games</div>
    <div class="container">
        <div class="grid-x grid-margin-x">
            @forelse ($games as $game)
                <div class="cell small-6 medium-2 game">
                    <a href="{{ route('games.show', ['id' => $game->id]) }}">
                        <img class="game-image" src="{{ storage($game->thumbnail_url) }}">
                    </a>
                    <a href="{{ route('games.show', ['id' => 1]) }}" class="game-name">{{ $game->title }}</a>
                    <div class="game-creator">Created By: <a href="{{ route('users.profile', ['username' => $game->creator->username]) }}">{{ $game->creator->username }}</a></div>
                    <div class="game-playing">N/A playing</div>
                </div>
            @empty
                <div class="cell auto">There are currently no active games.</div>
            @endforelse
        </div>
    </div>
@endsection
