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
    'pageTitle' => $message->title,
    'bodyClass' => 'inbox-page'
])

@section('content')
    <div class="inbox-navigation">
        <div class="inbox-navigation-item">
            <a href="{{ route('dashboard') }}">Dashboard</a>
        </div>
        <div class="inbox-navigation-item">
            <a href="{{ route('account.inbox.index') }}">Inbox</a>
        </div>
        <div class="inbox-navigation-item">
            <a href="{{ route('account.inbox.show', ['id' => $message->id]) }}">{{ $message->title }}</a>
        </div>
    </div>
    <div class="container">
        <div class="grid-x grid-margin-x">
            <div class="cell small-4 medium-2 text-center">
                <div class="inbox-show-message-sender-avatar">
                    <a href="{{ route('users.profile', ['username' => $message->creator->username]) }}">
                        <img class="inbox-show-message-sender-avatar-image" src="{{ ($message->creator->id == 1) ? storage('web-img/icon.png') : storage($message->creator->headshot_url) }}">
                    </a>
                </div>
                <a href="{{ route('users.profile', ['username' => $message->creator->username]) }}" class="inbox-show-message-sender-username">{{ $message->creator->username }}</a>
            </div>
            <div class="cell small-8 medium-10">
                <div class="grid-x grid-margin-x">
                    <div class="auto cell">
                        <div class="inbox-show-message-title">{{ $message->title }}</div>
                    </div>
                    @if ($message->sender_id != Auth::user()->id)
                        <div class="shrink cell">
                            <a href="{{ route('account.inbox.reply', ['id' => $message->id]) }}" class="button button-blue">Reply</a>
                        </div>
                    @endif
                </div>
                @if ($message->receiver_id == Auth::user()->id)
                    <div class="inbox-show-message-received">Received {{ $message->created_at->diffForHumans() }}</div>
                @else
                    <div class="inbox-show-message-received">Sent {{ $message->created_at->diffForHumans() }}</div>
                @endif
                <hr class="inbox-show-message-divider">
                <div class="inbox-show-message-body">{!! nl2br(e($message->body)) !!}</div>
            </div>
        </div>
    </div>
@endsection
