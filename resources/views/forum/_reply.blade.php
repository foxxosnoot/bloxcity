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

<div class="forum-container forum-post-container @if ($post->deleted && !$isThread) is-deleted @endif" @if (!$isThread) id="reply_{{ $post->id }}" @endif>
    <div class="grid-x grid-margin-x">
        <div class="cell small-4 medium-3 text-center">
            <div class="forum-thread-creator-username">
                @if ($post->creator->online())
                    <div class="forum-thread-status status-online" title="{{ $post->creator->username }} is online" data-tooltip></div>
                @else
                    <div class="forum-thread-status status-offline" title="{{ $post->creator->username }} is offline" data-tooltip></div>
                @endif
                <a href="{{ route('users.profile', ['username' => $post->creator->username]) }}">{{ $post->creator->username }}</a>
                @if ($post->creator->usernameHistory()->count() > 0)
                    @php
                        $i = 1;
                        $len = $post->creator->usernameHistory()->count();
                    @endphp
                    <i
                        class="icon icon-username-history forum-username-history"
                        title="Previous usernames: @foreach ($post->creator->usernameHistory() as $username) {{ $username->username }}@php if ($i < $len) { echo ', '; } $i++; @endphp @endforeach"
                        data-position="right"
                        data-tooltip
                    ></i>
                @endif
            </div>
            <a href="{{ route('users.profile', ['username' => $post->creator->username]) }}">
                <img class="forum-thread-creator-avatar" style="margin-top:10px;margin-bottom:10px;" src="{{ storage($post->creator->avatar_url) }}">
            </a>
            {!! $post->creator->forumBanner() !!}
            <div class="forum-thread-stats">
                <div class="grid-x grid-margin-x">
                    <div class="cell small-6 medium-3 medium-offset-3">
                        <strong>Posts</strong>
                    </div>
                    <div class="cell small-6 medium-3">
                        {{ number_format($post->creator->postCount()) }}
                    </div>
                </div>
                <div class="grid-x grid-margin-x">
                    <div class="cell small-6 medium-3 medium-offset-3">
                        <strong>Joined</strong>
                    </div>
                    <div class="cell small-6 medium-3">
                        {{ $post->creator->created_at->format('m/d/y') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="cell small-8 medium-9">
            <div class="forum-thread-time-posted"><i class="icon icon-time-ago"></i> Posted {{ $post->created_at->diffForHumans() }}</div>
            @auth
                @if ($post->creator->id != 1)
                    <a href="{{ route('report.index', ['type' => ($isThread) ? 'forum-thread' : 'forum-reply', 'id' => $post->id]) }}" class="forum-thread-report">
                        <i class="icon icon-report"></i>
                    </a>
                @endif
                @if (!$isThread)
                    <a href="{{ route('forum.quote', ['id' => $post->id]) }}" class="forum-thread-quote">
                        <i class="icon icon-quote"></i>
                    </a>
                @endif
            @endauth
            @if (!$isThread && !empty($post->quote_id))
                <div class="forum-quote">
                    @if ($post->quote->creator->power < 4)
                        <div class="forum-quote-body">{!! nl2br(e($post->quote->body)) !!}</div>
                    @else
                        <div class="forum-quote-body">{!! nl2br($post->quote->body) !!}</div>
                    @endif
                    <div class="forum-quote-footer"><a href="{{ route('users.profile', ['username' => $post->quote->creator->username]) }}">{{ $post->quote->creator->username }}</a>, {{ $reply->quote->created_at->format('m-d Y h:i A') }}</div>
                </div>
            @endif
            @if ($post->creator->power < 4)
                <div class="forum-thread-body">{!! nl2br(e($post->body)) !!}</div>
            @else
                <div class="forum-thread-body">{!! nl2br($post->body) !!}</div>
            @endif
            @if (!empty($post->creator->signature))
                <div class="forum-signature">{{ $post->creator->signature }}</div>
            @endif
            @auth
                @if (Auth::user()->power > 1)
                    <div class="forum-mod-tools">
                        @if ($isThread)
                            <div class="forum-mod-tool">
                                <a href="{{ route('forum.moderate', ['type' => 'thread', 'id' => $post->id, 'action' => 'switch_delete']) }}">{{ ($post->deleted) ? 'Undelete' : 'Delete' }}</a>
                            </div>
                            <div class="forum-mod-tool">
                                <a href="{{ route('forum.moderate', ['type' => 'thread', 'id' => $post->id, 'action' => 'scrub_title']) }}">Scrub Title</a>
                            </div>
                            <div class="forum-mod-tool">
                                <a href="{{ route('forum.moderate', ['type' => 'thread', 'id' => $post->id, 'action' => 'scrub_body']) }}">Scrub Body</a>
                            </div>
                            @if (Auth::user()->power >= 4)
                                <div class="forum-mod-tool">
                                    <a href="{{ route('forum.edit', ['type' => 'thread', 'id' => $post->id]) }}">Edit Post</a>
                                </div>
                            @endif
                            <div class="forum-mod-tool">
                                <a href="{{ route('forum.moderate', ['type' => 'thread', 'id' => $post->id, 'action' => 'switch_lock']) }}">{{ ($post->locked) ? 'Unlock' : 'Lock' }}</a>
                            </div>
                            <div class="forum-mod-tool">
                                <a href="{{ route('forum.moderate', ['type' => 'thread', 'id' => $post->id, 'action' => 'switch_pin']) }}">{{ ($post->pinned) ? 'Unpin' : 'Pin' }}</a>
                            </div>
                            <div class="forum-mod-tool">
                                <a href="{{ route('forum.move', ['id' => $post->id]) }}">Move</a>
                            </div>
                            <div class="forum-mod-tool">
                                <a href="{{ route('admin.ban', ['username' => $post->creator->username]) }}">Ban Poster</a>
                            </div>
                        @else
                            <div class="forum-mod-tool">
                                <a href="{{ route('forum.moderate', ['type' => 'reply', 'id' => $post->id, 'action' => 'switch_delete']) }}">{{ ($post->deleted) ? 'Undelete' : 'Delete' }}</a>
                            </div>
                            <div class="forum-mod-tool">
                                <a href="{{ route('forum.moderate', ['type' => 'reply', 'id' => $post->id, 'action' => 'scrub']) }}">Scrub</a>
                            </div>
                            @if (Auth::user()->power >= 4)
                                <div class="forum-mod-tool">
                                    <a href="{{ route('forum.edit', ['type' => 'reply', 'id' => $post->id]) }}">Edit Post</a>
                                </div>
                            @endif
                            <div class="forum-mod-tool">
                                <a href="{{ route('admin.ban', ['username' => $post->creator->username]) }}">Ban Poster</a>
                            </div>
                        @endif
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>
