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
    $defaultPageDescription =  config('app.name') . ' is a free creative gaming platform designed for kids and teenagers. Play today!';
    $cssFile = 'css/light-theme.css';

    if (Auth::check()) {
        switch (Auth::user()->theme) {
            case 'light':
                $cssFile = 'css/light-theme.css';
                break;
            case 'dark':
                $cssFile = 'css/dark-theme.css';
                break;
        }
    }
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<meta name="author" content="{{ config('app.name') }}">
<meta name="description" content="{{ $pageDescription ?? $defaultPageDescription }}">
<meta name="keywords" content="{{ config('app.name') }}, roblox alternative, building game, free online games">
<link rel="shortcut icon" href="{{ storage('web-img/icon.png') }}">

<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ str_replace(' | ' . config('app.name'), '', $title) }}">
<meta property="og:description" content="{{ $pageDescription ?? $defaultPageDescription }}">
<meta property="og:image" content="{{ $pageImage ?? storage('web-img/icon.png') }}">
@guest
    <meta name="user-data" data-authenticated="false">
@else
    <meta
        name="user-data"
        data-authenticated="true"
        data-id="{{ Auth::user()->id }}"
        data-username="{{ Auth::user()->username }}"
        data-vip="{{ Auth::user()->vip > 0 }}"
        data-coins="{{ Auth::user()->currency_coins }}"
        data-cash="{{ Auth::user()->currency_cash }}"
        data-angle="{{ Auth::user()->angle }}"
    >
@endguest
@yield('additional_meta')

<meta name="csrf-param" content="_token">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Hind:400,500,600,700">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
@yield('additional_fonts')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/css/foundation.min.css">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.1/css/all.css">
<link rel="stylesheet" href="{{ mix($cssFile) }}">
@yield('additional_css')
