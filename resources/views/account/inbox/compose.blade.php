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
    'pageTitle' => 'Compose Message',
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
            <a href="{{ route('account.inbox.compose', ['username' => $user->username]) }}">Compose</a>
        </div>
    </div>
    <div class="container">
        <form action="{{ route('account.inbox.compose.store') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input class="form-input" type="text" name="title" placeholder="Title">
            <textarea class="form-input" name="body" placeholder="Write your message here." rows="5"></textarea>
            <button class="inbox-button" type="submit">Send</button>
        </form>
    </div>
@endsection
