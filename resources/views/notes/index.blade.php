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
    'pageTitle' => $title,
    'bodyClass' => 'notes-page',
    'gridFluid' => true
])

@section('content')
    <div class="grid-x grid-margin-x">
        <div class="cell medium-3">
            <h5>Notes</h5>
            <div class="container notes-sidebar">
                <a href="{{ route('notes', ['page' => 'terms']) }}" class="notes-sidebar-item @if ($active == 'terms') active @endif">Terms of Service</a>
                <a href="{{ route('notes', ['page' => 'privacy']) }}" class="notes-sidebar-item @if ($active == 'privacy') active @endif">Privacy Policy</a>
                <a href="{{ route('notes', ['page' => 'about']) }}" class="notes-sidebar-item @if ($active == 'about') active @endif">About</a>
                <a href="{{ route('notes', ['page' => 'jobs']) }}" class="notes-sidebar-item @if ($active == 'jobs') active @endif">Jobs</a>
                <a href="{{ route('notes', ['page' => 'team']) }}" class="notes-sidebar-item @if ($active == 'team') active @endif">Team</a>
                <a href="{{ route('notes', ['page' => 'contact']) }}" class="notes-sidebar-item @if ($active == 'contact') active @endif">Contact</a>
            </div>
            <div class="push-25 show-for-small-only"></div>
        </div>
        <div class="cell medium-9">
            <h5>{{ $title }}</h5>
            <div class="container">
                @include($file)
            </div>
        </div>
    </div>
@endsection
