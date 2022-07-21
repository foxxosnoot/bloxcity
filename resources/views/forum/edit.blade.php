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
    'pageTitle' => ($type == 'thread') ? 'Edit Thread' : 'Edit Reply',
    'bodyClass' => 'forum-page',
    'gridClass' => 'forum-grid'
])

@section('content')
    <div class="show-for-small-only text-center">
        <a href="{{ route('forum.my-threads') }}" class="button button-blue">My Threads</a>
        <a href="{{ route('forum.search') }}" class="button button-red">Search Forum</a>
    </div>
    <div class="grid-x grid-margin-x">
        <div class="cell small-9 medium-6">
            <div class="forum-navigation">
                <div class="forum-navigation-item">
                    <a href="{{ route('forum.index') }}">Forum</a>
                </div>
                <div class="forum-navigation-item">
                    <a href="{{ route('forum.index') }}">{{ config('app.name') }}</a>
                </div>
                <div class="forum-navigation-item">
                    <a href="{{ route('forum.topic', ['id' => $post->topic->id ?? $post->thread->topic->id ]) }}">{{ $post->topic->name ?? $post->thread->topic->name }}</a>
                </div>
            </div>
        </div>
        <div class="cell medium-6 text-right hide-for-small-only">
            <div class="forum-auth-navigation">
                <div class="forum-auth-navigation-item">
                    <a href="{{ route('forum.my-threads') }}">My Threads</a>
                </div>
                <div class="forum-auth-navigation-item">
                    <a href="{{ route('forum.search') }}">Search Forum</a>
                </div>
            </div>
        </div>
    </div>
    <div class="forum-header forum-thread-header">
        Edit {{ ($type == 'thread') ? 'Thread' : 'Reply' }}
    </div>
    <div class="container forum-container">
        <form action="{{ route('forum.edit.update') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="type" value="{{ $type }}">
            @if ($type == 'thread')
                <input class="form-input" type="text" name="title" placeholder="Title" value="{{ $post->title }}">
                <textarea class="form-input" name="body" placeholder="Write your post here." rows="5">{{ $post->body }}</textarea>
            @elseif ($type == 'reply')
                <textarea class="form-input" name="body" placeholder="Write your post here." rows="5">{{ $post->body }}</textarea>
            @endif
            <button class="forum-button" type="submit">Edit</button>
        </form>
    </div>
@endsection
