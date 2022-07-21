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
    'pageTitle' => 'Site Offline',
    'pageDescription' =>  config('app.name') . ' is offline for maintenance and upgrades to improve your browsing experience.',
    'bodyClass' => 'maintenance-page',
    'gridClass' => 'maintenance-grid',
    'blank' => true
])

@section('additional_meta')
    <meta http-equiv="refresh" content="30; url=/">
@endsection

@section('additional_fonts')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Asap:400,700">
@endsection

@section('content')
    <div class="maintenance-container">
        <div class="grid-x grid-margin-x">
            <div class="cell medium-5 hide-for-small-only">
                <img src="{{ storage('web-img/builder.png') }}">
            </div>
            <div class="cell medium-7">
                <h2 class="maintenance-title">{{ config('app.name') }} is Currently Offline</h2>
                <div class="maintenance-description">{{ config('app.name') }} is offline for maintenance and upgrades to improve your browsing experience. We'll redirect you when we are finished. Please check back soon!</div>
                <div class="maintenance-socials">
                    <a href="{{ config('blox.socials.discord') }}" target="_blank"><i class="fab fa-discord"></i></a>
                    <a href="{{ config('blox.socials.twitter') }}" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="{{ config('blox.socials.youtube') }}" target="_blank"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="dev-button" data-toggle="login-modal"></div>
    <div class="modal reveal" id="login-modal" data-reveal>
        <form action="{{ route('maintenance.authenticate') }}" method="POST">
            {{ csrf_field() }}
            <div class="modal-title">Maintenance Login</div>
            <div class="modal-content">
                <input class="form-input" type="password" name="passcode" placeholder="Passcode" required>
            </div>
            <div class="modal-footer">
                <div class="modal-buttons">
                    <button class="modal-button">LOGIN</button>
                    <button class="modal-button" data-close>CANCEL</button>
                </div>
            </div>
        </form>
    </div>
@endsection
