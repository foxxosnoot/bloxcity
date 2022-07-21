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
    'pageTitle' => 'Inbox',
    'bodyClass' => 'inbox-page'
])

@section('content')
    <div class="tabs inbox-tabs">
        <div class="tab">
            <a href="{{ route('account.inbox.index', ['sort' => 'incoming']) }}" class="tab-link @if (request()->sort == 'incoming') active @endif">Incoming</a>
        </div>
        <div class="tab">
            <a href="{{ route('account.inbox.index', ['sort' => 'sent']) }}" class="tab-link @if (request()->sort == 'sent') active @endif">Sent</a>
        </div>
        <div class="tab">
            <a href="{{ route('account.inbox.index', ['sort' => 'history']) }}" class="tab-link @if (request()->sort == 'history') active @endif">History</a>
        </div>
    </div>
    <div class="inbox-messages">
        @forelse ($messages as $message)
            <div class="inbox-container @if ($message->seen) is-seen @endif">
                <div class="inbox-message-sender-avatar">
                    {{-- @if (request()->sort == 'incoming' && $message->receiver_id == Auth::user()->id) --}}
                        <img class="inbox-message-sender-avatar-image" src="{{ ($message->creator->id == 1) ? storage('web-img/icon.png') : storage($message->creator->headshot_url) }}">
                    {{-- @else
                        <img class="inbox-message-sender-avatar-image" src="{{ ($message->receiver->id == 1) ? storage('web-img/icon.png') : storage($message->receiver->headshot_url) }}">
                    @endif --}}
                </div>
                <div class="inbox-message-details">
                    <a href="{{ route('account.inbox.show', ['id' => $message->id]) }}" class="inbox-message-title">{{ $message->title }}</a>
                    {{-- @if (request()->sort == 'incoming' && $message->receiver_id == Auth::user()->id) --}}
                        <div class="inbox-message-sender">from <a href="{{ route('users.profile', ['username' => $message->creator->username]) }}">{{ $message->creator->username }}</a> {{ $message->created_at->diffForHumans() }}</div>
                    {{-- @else
                        <div class="inbox-message-sender">to <a href="{{ route('users.profile', ['username' => $message->receiver->username]) }}">{{ $message->receiver->username }}</a> {{ $message->created_at->diffForHumans() }}</div>
                    @endif --}}
                </div>
            </div>
        @empty
            <div class="container inbox-container" style="padding-bottom:15px;">
                <p>{{ $string }}</p>
            </div>
        @endforelse
    </div>
    {{ $messages->onEachSide(1)->links('pagination.default') }}
@endsection
