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
    'pageTitle' => 'Reply to Message',
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
            <a href="{{ route('account.inbox.reply', ['id' => $message->id]) }}">Reply</a>
        </div>
    </div>
    <div class="container">
        <div class="inbox-quote">
            <div class="forum-quote-body">{!! nl2br(e($message->body)) !!}</div>
            <div class="forum-quote-footer"><a href="{{ route('users.profile', ['username' => $message->creator->username]) }}">{{ $message->creator->username }}</a>, {{ $message->created_at->format('m-d Y h:i A') }}</div>
        </div>
        <form action="{{ route('account.inbox.reply.store') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="message_id" value="{{ $message->id }}">
            <textarea class="form-input" name="body" placeholder="Write your message here." rows="5"></textarea>
            <button class="inbox-button" type="submit">Send</button>
        </form>
    </div>
@endsection
