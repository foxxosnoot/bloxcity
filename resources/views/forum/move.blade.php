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
    'pageTitle' => 'Move "'. $thread->title .'"',
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
                    <a href="{{ route('forum.topic', ['id' => $thread->topic->id]) }}">{{ $thread->topic->name }}</a>
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
        Move "{{ $thread->title }}"
    </div>
    <div class="container forum-container">
        <form action="{{ route('forum.move.update') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="thread_id" value="{{ $thread->id }}">
            <strong>Move thread to...</strong>
            <select class="form-input" name="topic">
                @foreach ($topics as $topic)
                    <option value="{{ $topic->id }}" @if ($thread->topic_id == $topic->id) selected @endif>{{ $topic->name }}</option>
                @endforeach
            </select>
            <button class="forum-button" type="submit">Move</button>
        </form>
    </div>
@endsection
