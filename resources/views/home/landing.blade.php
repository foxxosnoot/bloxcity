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
    'pageTitle' => 'Landing',
    'bodyClass' => 'landing-page',
    'gridFluid' => true
])

@section('additional_fonts')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300">
@endsection

@section('content')
    <div class="grid-x">
        <div class="cell medium-4 medium-offset-8">
            <div class="landing-container">
                <div class="landing-header">The place to create</div>
                <div class="landing-text">Create an account for free.</div>
                @if (!settings('registration_enabled'))
                    <p class="landing-text">We're sorry, account creation is currently disabled. Please try again later.</p>
                @else
                    <form action="{{ route('register.authenticate') }}" method="POST">
                        {{ csrf_field() }}
                        <input class="form-input" type="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                        <input class="form-input" type="text" name="username" placeholder="Choose a username" value="{{ old('username') }}" required>
                        <input class="form-input" type="password" name="password" placeholder="Create a password" required>
                        <input class="form-input" type="password" name="password_confirmation" placeholder="Type password again" required>
                        <input class="form-checkbox" type="checkbox" name="accept_tos">
                        <label class="form-label" for="accept_tos">I agree to follow the <a href="/notes/terms">terms of service</a></label>
                        <div class="push-15"></div>
                        <button class="button button-block button-blue" type="submit">Sign Up</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
