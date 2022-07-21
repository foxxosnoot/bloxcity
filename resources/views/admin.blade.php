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
    if (isset($pageTitle)) {
        $title = $pageTitle . ' | BC Administration';
    } else {
        $title = 'BC Administration';
    }

    $random = rand();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    @include('layout.metadata-admin')
    <title>{{ $title }}</title>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        @include('layout.topbar-admin')
        @include('layout.sidebar-admin')
        <div class="content-wrapper">
            @yield('header')
            <section class="content container-fluid">
                @include('layout.alerts-admin')
                @yield('content')
            </section>
        </div>
        @include('layout.footer-admin')
    </div>

    @include('layout.scripts-admin')
</body>
</html>
