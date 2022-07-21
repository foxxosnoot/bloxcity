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
    'pageTitle' => 'Groups',
    'bodyClass' => 'groups-page',
    'gridFluid' => true
])

@section('content')
    <div class="container mb-15">
        <div class="grid-x align-middle mb-15">
            <div class="cell auto">
                <h5>Search Groups</h5>
            </div>
            @auth
                <div class="cell shrink">
                    <a href="{{ route('creator-area.create', ['type' => 'group']) }}" class="button button-green">Create</a>
                </div>
            @endauth
        </div>
        <form method="GET">
            <input class="form-input" type="text" name="search" value="{{ request()->search }}" placeholder="Search and press enter">
        </form>
    </div>
    @forelse ($groups as $group)
        <div class="container group-container">
            <div class="grid-x grid-margin-x align-middle">
                <div class="cell small-12 medium-2 text-center">
                    <a href="{{ route('groups.show', ['id' => $group->id]) }}">
                        <img class="group-icon" src="{{ storage($group->thumbnail_url) }}">
                    </a>
                </div>
                <div class="cell small-12 medium-8">
                    <a href="{{ route('groups.show', ['id' => $group->id]) }}" class="group-name">{{ $group->name }}</a>
                    <div class="group-description">{{ Str::limit($group->description, 400) }}</div>
                </div>
                <div class="cell small-12 medium-2 text-center">
                    <div class="group-member-count">0</div>
                    <div class="group-members-text">Members</div>
                </div>
            </div>
        </div>
    @empty
        <div class="container">
            <p>No groups found.</p>
        </div>
    @endforelse
    {{ $groups->onEachSide(1)->links('pagination.default') }}
@endsection
