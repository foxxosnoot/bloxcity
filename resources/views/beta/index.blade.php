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
    'pageTitle' => 'Beta Program',
    'bodyClass' => 'discord-page',
    'gridClass' => 'discord-grid'
])

@section('content')
    <h5>Beta</h5>
    <div class="container">
        <div class="text-center">
            @if (!Auth::user()->beta_tester)
                <i class="icon icon-discord"></i>
                <div class="discord-title">Join the Beta Program</div>
                <p>Click the 'Join' button below to join the beta program.</p>
                <form action="{{ route('beta.update') }}" method="POST">
                    {{ csrf_field() }}
                    <button class="button button-block button-green">Join</button>
                </form>
            @else
                <i class="icon icon-discord has-verified"></i>
                <div class="discord-title">Leave the Beta Program</div>
                <p>Click the 'Leave' button below to leave the beta program.</p>
                <form action="{{ route('beta.update') }}" method="POST">
                    {{ csrf_field() }}
                    <button class="button button-block button-red">Leave</button>
                </form>
            @endif
        </div>
    </div>
@endsection
