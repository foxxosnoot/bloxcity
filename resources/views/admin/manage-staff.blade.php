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

@extends('admin', [
    'pageTitle' => 'Manage Staff'
])

@section('header')
    <section class="content-header">
        <h1>
            Manage Staff
            <small>Change Ranks of Staff</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage Staff</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Search for user</h3>
        </div>
        <div class="box-body">
            <form method="GET">
                <div class="input-group">
                    <input class="form-control" type="text" name="search" placeholder="Username" value="{{ request()->search }}">
                    <span class="input-group-btn">
                        <button class="btn btn-flat btn-primary" type="submit">Search</button>
                    </span>
                </div>
            </form>
        </div>
    </div>
    @if (request()->has('search') && request()->search != '' && !$user)
        <div class="box box-primary">
            <div class="box-body">
                <p>No results.</p>
            </div>
        </div>
    @endif
    @if ($user)
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title text-primary">{{ $user->username }}</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6 col-lg-3 text-center">
                        <img class="img-responsive" src="{{ storage($user->avatar_url) }}">
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('admin.manage_staff.update') }}" method="POST">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                            <label for="rank">Rank</label>
                            <select class="form-control" name="rank" required>
                                @if (Auth::user()->id == 1) <option value="5" @if ($user->power == 5) selected @endif>System</option> @endif
                                @if (Auth::user()->power >= 5 || Auth::user()->id == 1) <option value="4" @if ($user->power == 4) selected @endif>Executive Administrator</option> @endif
                                @if (Auth::user()->power >= 4 || Auth::user()->id == 1) <option value="3" @if ($user->power == 3) selected @endif>Administrator</option> @endif
                                @if (Auth::user()->power >= 3 || Auth::user()->id == 1) <option value="2" @if ($user->power == 2) selected @endif>Moderator</option> @endif
                                @if (Auth::user()->power >= 2 || Auth::user()->id == 1) <option value="1" @if ($user->power == 1) selected @endif>Asset Creator</option> @endif
                                <option value="0" @if ($user->power == 0) selected @endif>Regular User</option>
                            </select>
                            <button class="btn btn-primary" type="submit">Update Rank</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
