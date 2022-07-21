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
    'pageTitle' => $group->name,
    'bodyClass' => 'group-page'
])

@section('content')
    <div class="container mb-25">
        <div class="grid-x grid-margin-x">
            <div class="cell small-12 medium-3 text-center">
                <img src="{{ storage($group->thumbnail_url) }}" class="group-icon">
                <div class="push-25"></div>
                <button class="button button-block button-green">Join</button>
                <div class="push-10"></div>
                <a href="{{ route('groups.edit', ['id' => $group->id]) }}" class="button button-block button-blue">Manage</a>
            </div>
            <div class="cell small-12 medium-9">
                <div class="group-name">{{ $group->name }}</div>
                <div class="group-description">{{ $group->description }}</div>
            </div>
        </div>
    </div>
    <div class="container group-stats-container mb-25">
        <div class="grid-x grid-margin-x">
            <div class="cell small-12 medium-4">
                <div class="group-stat-result"><a href="{{ route('users.profile', ['username' => $group->owner->username]) }}">{{ $group->owner->username }}</a></div>
                <div class="group-stat-name">Owner</div>
                <div class="push-15 show-for-small-only"></div>
            </div>
            <div class="cell small-12 medium-4">
                <div class="group-stat-result">0</div>
                <div class="group-stat-name">Members</div>
                <div class="push-15 show-for-small-only"></div>
            </div>
            <div class="cell small-12 medium-4">
                <div class="group-stat-result group-stat-vault">${{ $group->vault }}</div>
                <div class="group-stat-name">Vault</div>
            </div>
        </div>
    </div>
    <div class="container group-members-container">
        <div class="grid-x grid-margin-x align-middle">
            <div class="cell auto">
                <h5>Members</h5>
            </div>
            <div class="cell shrink">
                <select class="form-input" id="ranks">
                    <option value="1">Member (1)</option>
                    <option value="2">Moderator (0)</option>
                    <option value="3">Admin (0)</option>
                    <option value="4">Owner (0)</option>
                </select>
            </div>
        </div>
        <div id="members">
            <div class="grid-x grid-margin-x">
                <div class="cell small-6 medium-2 group-member">
                    <a href="/profile/BLOXCity">
                        <img class="group-member-avatar" src="https://cdn.discordapp.com/attachments/769236874790174831/795622874483392512/OuU0MQ6pxT2S8kDfwghdtENaL.png">
                    </a>
                    <a href="/profile/BLOXCity">BLOXCity</a>
                </div>
            </div>
        </div>
    </div>
@endsection
