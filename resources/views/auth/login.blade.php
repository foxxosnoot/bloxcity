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
    'pageTitle' => 'Login',
    'bodyClass' => 'auth-page'
])

@section('content')
    <div class="grid-x">
        <div class="cell medium-6 medium-offset-4">
            <div class="container auth-container">
                <h5 class="mb-25">Log in</h5>
                <form action="{{ route('login.authenticate') }}" method="POST">
                    {{ csrf_field() }}
                    <input class="form-input" type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
                    <input class="form-input" type="password" name="password" placeholder="Password" required>
                    <div class="grid-x align-middle">
                        <div class="cell auto">
                            <button class="button button-blue" type="submit">Log in</button>
                        </div>
                        <div class="cell shrink">
                            {{-- <a href="/forgot-password">Forgot password?</a> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
