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
    'pageTitle' => $thread->title,
    'pageDescription' => $thread->title . ' is a forum post on ' . config('app.name') . ' by ' . $thread->creator->username . '. Join them in creating awesome friendships, items, games, and more!',
    'pageImage' => storage($thread->creator->headshot_url),
    'bodyClass' => 'forum-page',
    'gridClass' => 'forum-grid'
])

@section('content')
    @if ($thread->deleted)
        <div class="alert alert-error">
            <div class="grid-x grid-margin-x align-middle">
                <div class="cell shrink left">
                    <i class="icon icon-error"></i>
                </div>
                <div class="cell auto text-center">
                    This thread is deleted.
                    &nbsp;&nbsp;
                    <a href="{{ route('forum.moderate', ['type' => 'thread', 'id' => $thread->id, 'action' => 'switch_delete']) }}" class="button button-green" style="font-size:11px;">Undelete</a>
                </div>
                <div class="cell shrink right">
                    <i class="icon icon-error"></i>
                </div>
            </div>
        </div>
    @endif
    @auth
        <div class="show-for-small-only text-center">
            <a href="{{ route('forum.my-threads') }}" class="button button-blue">My Threads</a>
            <a href="{{ route('forum.search') }}" class="button button-red">Search Forum</a>
        </div>
    @endauth
    <div class="grid-x grid-margin-x">
        <div class="cell small-12 medium-6">
            <div class="forum-navigation">
                <div class="forum-navigation-item">
                    <a href="{{ route('forum.index') }}">Forum</a>
                </div>
                <div class="forum-navigation-item">
                    <a href="{{ route('forum.index') }}">{{ config('app.name') }}</a>
                </div>
                <div class="forum-navigation-item">
                    <a href="{{ route('forum.topic', ['id' => $thread->topic()->id]) }}">{{ $thread->topic()->name }}</a>
                </div>
            </div>
        </div>
        @auth
            <div class="cell small-12 medium-6 text-right hide-for-small-only">
                <div class="forum-auth-navigation">
                    <div class="forum-auth-navigation-item">
                        <a href="{{ route('forum.my-threads') }}">My Threads</a>
                    </div>
                    <div class="forum-auth-navigation-item">
                        <a href="{{ route('forum.search') }}">Search Forum</a>
                    </div>
                </div>
            </div>
        @endauth
    </div>
    <div class="forum-header forum-thread-header">
        {{ $thread->title }}
    </div>
    <div class="container">
        @if ($replies->onFirstPage())
            @include('forum._reply', ['post' => $thread, 'isThread' => true])
        @endif
        @foreach ($replies as $reply)
            @include('forum._reply', ['post' => $reply, 'isThread' => false])
        @endforeach
        {{ $replies->onEachSide(1)->links('pagination.default') }}
    </div>
    @auth
        @if (!$thread->locked || ($thread->locked && Auth::user()->power > 1))
            <div class="push-15"></div>
            <div class="text-center">
                <a href="{{ route('forum.reply', ['id' => $thread->id]) }}" class="forum-button">Reply</a>
            </div>
        @endif
    @endauth
@endsection
