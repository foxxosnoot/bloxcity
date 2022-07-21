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
    'pageTitle' => 'Admin'
])

@section('additional_js')
    <script>
        var ownsTroll = false;

        function troll()
        {
            $('#mainContainer').hide();
            $('#trollContainer').show();
        }

        function untroll()
        {
            $('#trollContainer').hide();
            $('#mainContainer').show();
        }
    </script>
@endsection

@section('content')
    <h5>{{ config('app.name') }} Admin Panel</h5>
    <div class="container" id="mainContainer">
        <p>Welcome, {{ (Auth::check()) ? Auth::user()->username : 'guest' }}!</p>
        <p>What would you like to do?</p>
        <button onclick="troll()" class="button button-red">Ban User</button>
        <button onclick="troll()" class="button button-red">Enable Maintenance</button>
        <button onclick="troll()" class="button button-red">Change Site Banner</button>
        <button onclick="troll()" class="button button-red">Create Item</button>
        <button onclick="troll()" class="button button-red">Grant Currency</button>
    </div>
    <div class="container text-center" id="trollContainer" style="display:none;">
        <h4>You just got trolled</h4>
        <div>
            <img src="{{ asset('frick.png') }}">
        </div>
        <button onclick="untroll()" class="button button-blue">Go Back</button>
    </div>
@endsection
