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
    'pageTitle' => 'Creator Area',
    'bodyClass' => 'create-page',
    'gridFluid' => true
])

@section('content')
    @if (!settings('creator_area_enabled'))
        <div class="container construction-container">
            <i class="icon icon-sad construction-icon"></i>
            <div class="construction-text">Sorry, the Creator Area is unavailable right now for maintenance. Try again later.</div>
        </div>
    @else
        <div class="container create-container">
            <h3 class="create-title">What do you wish to create?</h3>
            <div class="push-25"></div>
            <div class="grid-x grid-margin-x">
                @forelse ($links as $link)
                    <div class="cell small-6 medium-4 create-cell">
                        <a href="{{ route('creator-area.create', ['type' => $link['type']]) }}">
                            <img class="create-cell-image" src="{{ asset($link['image']) }}">
                            <div class="create-cell-title">{{ $link['title'] }}</div>
                        </a>
                    </div>
                @empty
                    <div class="cell auto">No create options found.</div>
                @endforelse
            </div>
        </div>
    @endif
@endsection
