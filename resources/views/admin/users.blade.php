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
    'pageTitle' => 'Users'
])

@section('header')
    <section class="content-header">
        <h1>
            Users
            <small>({{ $total }} total)</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Users</li>
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
                @if ($user->banned)
                    <div class="alert alert-danger">
                        <strong>Note:</strong> User is currently banned.
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-6 col-lg-3 text-center">
                        <img class="img-responsive" src="{{ storage($user->avatar_url) }}">
                        <br>
                        <a href="{{ route('users.profile', ['username' => $user->username]) }}" class="btn btn-fluid btn-primary" target="_blank">View</a>
                        <a href="{{ route('admin.ban', ['username' => $user->username]) }}" class="btn btn-fluid btn-danger" @if ($user->power >= Auth::user()->power) disabled @endif>Ban</a>
                        @if (Auth::user()->power > 1)
                            <br>
                            <br>
                            <div class="text-left">
                                <h3 class="text-danger font-weight-bold">Punishments</h3>
                                @forelse ($user->punishments() as $punishment)
                                    <div class="box box-danger" style="padding:5px;">
                                        <ul class="list-unstyled">
                                            <li><strong>Banned by:</strong> {{ $punishment->creator->username }} <a href="{{ route('admin.users', ['search' => $punishment->creator->username]) }}">[Click to view]</a></li></li>
                                            <li><strong>Category:</strong> {{ $punishment->category() }}</li>
                                            <li><strong>Length:</strong> {{ $punishment->length() }}</li>
                                            <li><strong>Mod Note:</strong> {{ $punishment->note ?? 'No note provided.' }}</li>
                                            <li><strong>Date:</strong> {{ $punishment->created_at->format('M d, Y') }}</li>
                                            <li><strong>Active:</strong> {{ ($punishment->active) ? 'Yes' : 'No' }}</li>
                                        </ul>
                                    </div>
                                @empty
                                    <p>User has no punishments.</p>
                                @endforelse
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6 col-lg-2">
                        <ul>
                            <li><strong>User ID:</strong> {{ $user->id }}</li>
                            <li><strong>Coins:</strong> {{ $user->currency_coins }}</li>
                            <li><strong>Cash:</strong> {{ $user->currency_cash }}</li>
                            <li><strong>Membership Level:</strong> <span style="color:{{ $user->membershipColor() }};font-weight:600;">{{ $user->membershipLevel() }}</span></li>
                            @if ($user->vip > 0) <li><strong>Membership Until:</strong> {{ $user->vip_until }}</li> @endif
                            <li><strong>Is Admin:</strong> {{ ($user->power > 0) ? 'Yes' : 'No' }}</li>
                            @if ($user->power > 0) <li><strong>Admin Level:</strong> {{ $user->adminRank() }}</li> @endif
                            <li><strong>Email:</strong> {{ $user->email }}</li>
                            <li><strong>Join Date:</strong> {{ $user->created_at->format('M d, Y') }}</li>
                            <li><strong>Last Seen:</strong> {{ $user->updated_at->format('M d, Y') }}</li>
                            <li><strong># Punishments:</strong> {{ $user->punishmentCount() }}</li>
                            <li><strong># Created Items:</strong> {{ $user->createdItems()->count() }}</li>
                            <li><strong># Created Games:</strong> 0</li>
                            <li><strong># Active Reports:</strong> N/A</li>
                        </ul>
                    </div>
                    @if (Auth::user()->power >= 4)
                        <div class="col-md-12 col-lg-7">
                            <h3 class="text-success font-weight-bold">Grant</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Currency</h4>
                                    <form action="{{ route('admin.users.grant') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="type" value="currency">
                                        <select class="form-control" name="currency" required>
                                            <option value="coins" selected>Coins</option>
                                            <option value="cash">Cash</option>
                                        </select>
                                        <input class="form-control" type="number" name="amount" placeholder="Amount" required>
                                        <button class="btn btn-success" type="submit">Grant</button>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <h4>Item</h4>
                                    <form action="{{ route('admin.users.grant') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="type" value="item">
                                        <input class="form-control" type="number" name="id" placeholder="ID" required>
                                        <button class="btn btn-success" type="submit">Grant</button>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <h4>VIP</h4>
                                    <form action="{{ route('admin.users.grant') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="type" value="vip">
                                        <select class="form-control" name="plan" required>
                                            <option value="1" selected>Bronze VIP</option>
                                            <option value="2">Silver VIP</option>
                                            <option value="3">Gold VIP</option>
                                            <option value="4">Platinum VIP</option>
                                        </select>
                                        <select class="form-control" name="time" required>
                                            <option value="1_month" selected>1 Month</option>
                                            <option value="3_months">3 Months</option>
                                            <option value="6_months">6 Months</option>
                                            <option value="12_months">12 Months</option>
                                            <option value="lifetime">Lifetime</option>
                                        </select>
                                        <button class="btn btn-success" type="submit">Grant</button>
                                    </form>
                                </div>
                            </div>
                            <br>
                            <h3 class="text-primary font-weight-bold">Modify</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Currency</h4>
                                    <form action="{{ route('admin.users.modify') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="type" value="currency">
                                        <select class="form-control" name="currency" required>
                                            <option value="coins" selected>Coins</option>
                                            <option value="cash">Cash</option>
                                        </select>
                                        <input class="form-control" type="number" name="amount" placeholder="Amount" required>
                                        <button class="btn btn-primary" type="submit">Modify</button>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <h4>VIP</h4>
                                    <form action="{{ route('admin.users.modify') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="type" value="vip">
                                        <select class="form-control" name="plan" required>
                                            <option value="0" @if ($user->vip == 0) selected @endif>None</option>
                                            <option value="1" @if ($user->vip == 1) selected @endif>Bronze VIP</option>
                                            <option value="2" @if ($user->vip == 2) selected @endif>Silver VIP</option>
                                            <option value="3" @if ($user->vip == 3) selected @endif>Gold VIP</option>
                                            <option value="4" @if ($user->vip == 4) selected @endif>Platinum VIP</option>
                                        </select>
                                        <button class="btn btn-primary" type="submit">Modify</button>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <h4>Username</h4>
                                    <form action="{{ route('admin.users.modify') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="type" value="username">
                                        <input class="form-control" type="text" name="username" placeholder="New Username" required>
                                        <button class="btn btn-primary" type="submit">Modify</button>
                                    </form>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Render</h4>
                                    <form action="{{ route('admin.users.modify') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="type" value="render">
                                        <button class="btn btn-block btn-primary" type="submit">Update</button>
                                    </form>
                                </div>
                            </div>
                            <br>
                        @endif
                        @if (Auth::user()->power >= 2)
                            <h3 class="text-danger font-weight-bold">Remove</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Avatar</h4>
                                    <form action="{{ route('admin.users.remove') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="type" value="avatar">
                                        <button class="btn btn-block btn-danger" type="submit">Reset</button>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <h4>Username</h4>
                                    <form action="{{ route('admin.users.remove') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="type" value="username">
                                        <button class="btn btn-block btn-danger" type="submit">Scrub</button>
                                    </form>
                                </div>
                                <div class="col-md-4">
                                    <h4>Description</h4>
                                    <form action="{{ route('admin.users.remove') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="type" value="description">
                                        <button class="btn btn-block btn-danger" type="submit">Scrub</button>
                                    </form>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-4">
                                    <h4>Signature</h4>
                                    <form action="{{ route('admin.users.remove') }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="type" value="signature">
                                        <button class="btn btn-block btn-danger" type="submit">Scrub</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection
