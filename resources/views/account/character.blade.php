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
    'pageTitle' => 'Character',
    'bodyClass' => 'character-page'
])

@section('additional_meta')
    <meta name="character-info" data-spinner="{{ storage('web-img/spinner.svg') }}">
@endsection

@section('additional_css')
    <style>
        .avatar-body-color {
            width: 50px;
            height: 50px;
            cursor: pointer;
            display: inline-block;
        }

        .avatar-body-color:not(:last-child) {
            margin-right: 5px
        }

        .avatar-body-color.inProgress {
            opacity: .8;
            pointer-events: none;
            cursor: not-allowed;
        }

        .avatar-item-category.active {
            font-weight: 600;
        }

        .avatar-body-part {
            outline: none;
            cursor: pointer;
        }

        .avatar-body-part:disabled {
            opacity: .8;
            pointer-events: none;
            cursor: not-allowed;
        }

        .character-item {
            position: relative;
        }

        .character-button {
            position: absolute;
            right: 0;
            top: 0;
            border-bottom: none!important;
            border-radius: 0;
        }

        .palette {
            background: #FFF;
            position: absolute;
            margin-left: 300px;
            margin-top: 308px;
            padding: 15px;
            border: 1px solid #CCC;
            z-index: 1337;
            display: none;
        }

        @media only screen and (max-width: 768px) {
            .palette {
                margin-top: 200px;
                margin-left: 20px;
            }
        }

        .palette-header-text {
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
            font-weight: 600;
        }
    </style>
@endsection

@section('additional_js')
    <script src="{{ asset('js/site/character.js?v=7') }}"></script>
@endsection

@section('content')
    @if (!settings('character_enabled'))
        <div class="container construction-container">
            <i class="icon icon-sad construction-icon"></i>
            <div class="construction-text">Sorry, Character Customization is unavailable right now for maintenance. Try again later.</div>
        </div>
    @else
        <div class="palette" id="colors">
            <div class="palette-header-text" id="colorsText">Choose a color</div>
            <div class="avatar-body-colors">
                <div class="avatar-body-color" style="background:#8d5524;" data-color="brown"></div>
                <div class="avatar-body-color" style="background:#c68642;" data-color="light-brown"></div>
                <div class="avatar-body-color" style="background:#e0ac69;" data-color="lighter-brown"></div>
                <div class="avatar-body-color" style="background:#f1c27d;" data-color="lighter-lighter-brown"></div>
                <div class="avatar-body-color" style="background:#fce1d5;" data-color="lighter-lighter-lighter-brown"></div>
            </div>
            <div class="avatar-body-colors">
                <div class="avatar-body-color" style="background:#f19d9a;" data-color="salmon"></div>
                <div class="avatar-body-color" style="background:#769fca;" data-color="blue"></div>
                <div class="avatar-body-color" style="background:#a2d1e6;" data-color="light-blue"></div>
                <div class="avatar-body-color" style="background:#a08bd0;" data-color="purple"></div>
                <div class="avatar-body-color" style="background:#312b4c;" data-color="dark-purple"></div>
            </div>
            <div class="avatar-body-colors">
                <div class="avatar-body-color" style="background:#046306;" data-color="dark-green"></div>
                <div class="avatar-body-color" style="background:#1b842c;" data-color="green"></div>
                <div class="avatar-body-color" style="background:#f7b155;" data-color="yellow"></div>
                <div class="avatar-body-color" style="background:#f79039;" data-color="orange"></div>
                <div class="avatar-body-color" style="background:#ff0000;" data-color="red"></div>
            </div>
            <div class="avatar-body-colors">
                <div class="avatar-body-color" style="background:#f8a3d5;" data-color="light-pink"></div>
                <div class="avatar-body-color" style="background:#ff0e9a;" data-color="pink"></div>
                <div class="avatar-body-color" style="background:#f1efef;" data-color="white"></div>
                <div class="avatar-body-color" style="background:#7d7d7d;" data-color="gray"></div>
                <div class="avatar-body-color" style="background:#000;" data-color="black"></div>
            </div>
        </div>
        <div class="grid-x grid-margin-x">
            <div class="cell medium-4">
                <div style="font-size:18px;">Avatar</div>
                <div class="container mb-15">
                    <img id="avatar" src="{{ storage(Auth::user()->avatar_url) }}" style="width:100%;">
                    <div id="avatarError" style="color:red;"></div>
                    <br>
                    <div class="text-center">
                        <button class="button button-primary" data-angle="left" @if (Auth::user()->angle == 'left') disabled @endif><i class="fas fa-arrow-left"></i></button>
                        <button class="button button-primary" data-angle="right" @if (Auth::user()->angle == 'right') disabled @endif><i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
                <div style="font-size:18px;">Colors</div>
                <div class="container text-center">
                    <div style="margin-bottom:2.5px;">
                        <button class="avatar-body-part" style="background-color:{{ rgb2hex(Auth::user()->rgb_head) }};padding:25px;margin-top:-1px;" data-part="head"></button>
                    </div>
                    <div style="margin-bottom:2.5px;">
                        <button class="avatar-body-part" style="background-color:{{ rgb2hex(Auth::user()->rgb_left_arm) }};padding:50px;padding-right:0px;" data-part="left_arm"></button>
                        <button class="avatar-body-part" style="background-color:{{ rgb2hex(Auth::user()->rgb_torso) }};padding:50px;" data-part="torso"></button>
                        <button class="avatar-body-part" style="background-color:{{ rgb2hex(Auth::user()->rgb_right_arm) }};padding:50px;padding-right:0px;" data-part="right_arm"></button>
                    </div>
                    <div>
                        <button class="avatar-body-part" style="background-color:{{ rgb2hex(Auth::user()->rgb_left_leg) }};padding:50px;padding-right:0px;padding-left:47px;" data-part="left_leg"></button>
                        <button class="avatar-body-part" style="background-color:{{ rgb2hex(Auth::user()->rgb_right_leg) }};padding:50px;padding-right:0px;padding-left:47px;" data-part="right_leg"></button>
                    </div>
                </div>
                <div class="push-15 show-for-small-only"></div>
            </div>
            <div class="cell medium-8">
                <div style="font-size:18px;">Inventory</div>
                <div class="container mb-15">
                    <div class="text-center mb-15">
                        <a class="avatar-item-category active" data-category="hats">Hats</a> |
                        <a class="avatar-item-category" data-category="faces">Faces</a> |
                        <a class="avatar-item-category" data-category="accessories">Accessories</a> |
                        <a class="avatar-item-category" data-category="t-shirts">T-Shirts</a> |
                        <a class="avatar-item-category" data-category="shirts">Shirts</a> |
                        <a class="avatar-item-category" data-category="pants">Pants</a> |
                        <a class="avatar-item-category" data-category="heads">Heads</a>
                    </div>
                    <div id="inventory"></div>
                    <div id="inventoryButtons"></div>
                </div>
                <div style="font-size:18px;">Currently Wearing</div>
                <div class="container">
                    <div id="currentlyWearing"></div>
                </div>
            </div>
        </div>
    @endif
@endsection
