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
    'pageTitle' => 'Forum',
    'bodyClass' => 'forum-page',
    'gridClass' => 'forum-grid'
])

@section('content')
    @if (!settings('forum_enabled'))
        <div class="container construction-container">
            <i class="icon icon-sad construction-icon"></i>
            <div class="construction-text">Sorry, the Forum is unavailable right now for maintenance. Try again later.</div>
        </div>
    @else
        <div class="forum-header">
            <div class="grid-x grid-margin-x">
                <div class="cell medium-8">
                    {{ config('app.name') }}
                </div>
                <div class="cell medium-1 text-center hide-for-small-only">
                    Threads
                </div>
                <div class="cell medium-1 text-center hide-for-small-only">
                    Replies
                </div>
                <div class="cell medium-2 text-right hide-for-small-only">
                    Last Post
                </div>
            </div>
        </div>
        @forelse ($topics as $topic)
            <div class="forum-container">
                <div class="grid-x grid-margin-x align-middle">
                    <div class="cell medium-8">
                        <a href="{{ route('forum.topic', ['id' => $topic->id]) }}">
                            <div class="forum-container-topic-name">{{ $topic->name }}</div>
                            <div class="forum-container-topic-description">{{ $topic->description }}</div>
                        </a>
                    </div>
                    <div class="cell medium-1 text-center hide-for-small-only">
                        <div class="forum-container-stat">{{ number_format($topic->threads()->count()) }}</div>
                    </div>
                    <div class="cell medium-1 text-center hide-for-small-only">
                        <div class="forum-container-stat">0</div>
                    </div>
                    <div class="cell medium-2 text-right hide-for-small-only">
                        @if (empty($topic->last_thread_id))
                            N/A
                        @else
                            <a href="{{ route('forum.thread', ['id' => $topic->lastThread->id]) }}" class="forum-container-stat forum-container-stat-last-post">{{ $topic->lastThread->title }}</a>
                            <div class="forum-container-stat forum-container-stat-last-poster">
                                by <a href="{{ route('users.profile', ['username' => $topic->lastPoster->username]) }}">{{ $topic->lastPoster->username }}</a>, {{ $topic->last_post_at->diffForHumans() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="forum-container">
                There are currently no forum topics.
            </div>
        @endforelse
    @endif
@endsection
