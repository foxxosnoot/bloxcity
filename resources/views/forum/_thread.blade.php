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

<div class="grid-x grid-margin-x align-middle forum-post-grid @if ($thread->deleted) is-deleted @endif">
    <div class="cell medium-8">
        <div class="forum-post-creator-avatar">
            <img class="forum-post-creator-avatar-image" src="{{ ($thread->creator->id == 1) ? storage('web-img/icon.png') : storage($thread->creator->headshot_url) }}">
        </div>
        <div class="forum-post-details">
            <a href="{{ route('forum.thread', ['id' => $thread->id]) }}" class="forum-post-name @if ($thread->pinned) forum-post-name-pinned @endif">{{ $thread->title }}</a>
            <div class="forum-post-poster">posted by <a href="{{ route('users.profile', ['username' => $thread->creator->username]) }}">{{ $thread->creator->username }}</a> {{ $thread->created_at->diffForHumans() }}</div>
        </div>
    </div>
    <div class="cell medium-1 text-center hide-for-small-only">
        <div class="forum-container-stat">{{ number_format($thread->replies()->count()) }}</div>
    </div>
    <div class="cell medium-1 text-center hide-for-small-only">
        <div class="forum-container-stat">{{ number_format($thread->views) }}</div>
    </div>
    <div class="cell medium-2 text-right hide-for-small-only">
        @if (empty($thread->last_poster_id))
            N/A
        @else
            <a href="{{ route('forum.thread', ['id' => $thread->id]) }}" class="forum-container-stat forum-container-stat-last-post">{{ $thread->title }}</a>
            <div class="forum-container-stat forum-container-stat-last-poster">
                @if (empty($thread->last_reply_id))
                    by <a href="{{ route('users.profile', ['username' => $thread->creator->username]) }}">{{ $thread->creator->username }}</a>, {{ $thread->created_at->diffForHumans() }}
                @else
                    by <a href="{{ route('users.profile', ['username' => $thread->lastPoster->username]) }}">{{ $thread->lastPoster->username }}</a>, {{ $thread->lastReply->created_at->diffForHumans() }}
                @endif
            </div>
        @endif
    </div>
</div>
