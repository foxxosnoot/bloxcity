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

@php
    $title = (isset($pageTitle)) ? $pageTitle .' | ' . config('app.name') : config('app.name');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    @include('layout.metadata')
    <title>{{ $title }}</title>
</head>
<body class="{{ $bodyClass ?? '' }}">
    <div id="app">
        @if (!isset($blank))
            @include('layout.topbar')
            @include('layout.sidebar')
            @include('layout.banner')
        @endif

        <div class="page-wrapper">
            <div class="grid-container {{ isset($gridFluid) ? 'fluid' : '' }} {{ $gridClass ?? '' }}">
                <div class="grid-x">
                    <div class="cell medium-10 medium-offset-1">
                        @include('layout.alerts')
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>

        @if (!isset($blank))
            @include('layout.footer')
        @endif
    </div>

    @include('layout.scripts')
</body>
</html>
