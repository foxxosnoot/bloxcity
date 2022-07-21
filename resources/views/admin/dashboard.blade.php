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
    'pageTitle' => 'Dashboard'
])

@section('header')
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control Panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <img class="img-responsive" src="{{ storage(Auth::user()->avatar_url) }}">
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h4 class="font-weight-bold">Hello, {{ Auth::user()->username }}!</h4>
                            <p><strong>Your Rank:</strong> {{ Auth::user()->adminRank() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Recent Actions</h3>
                </div>
                <div class="box-body">
                    <ul class="list-unstyled recent-actions">
                        <li>
                            <i class="fas fa-plus-circle text-success"></i>
                            <a href="#">Isaiah</a> created item <a href="#">test hat</a>.
                        </li>
                        <li>
                            <i class="fas fa-pencil text-orange"></i>
                            <a href="#">Isaiah</a> edited item <a href="#">test hat</a>.
                        </li>
                        <li>
                            <i class="fas fa-times-circle text-danger"></i>
                            <a href="#">Isaiah</a> deleted item <a href="#">test hat</a>.
                        </li>
                    </ul>
                    <p class="text-muted"><small>* This is dummy data</small></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-yellow">
                    <i class="fal fa-users"></i>
                </span>
                <div class="info-box-content">
                    <div class="info-box-text">Total Members</div>
                    <div class="info-box-number">{{ number_format($totalMembers) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-green">
                    <i class="fal fa-signal"></i>
                </span>
                <div class="info-box-content">
                    <div class="info-box-text">Users Online Now</div>
                    <div class="info-box-number">{{ number_format($usersOnlineNow) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-light">
                    <i class="fal fa-clock"></i>
                </span>
                <div class="info-box-content">
                    <div class="info-box-text">Registered Today</div>
                    <div class="info-box-number">{{ number_format($registeredToday) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-red">
                    <i class="fal fa-user-friends"></i>
                </span>
                <div class="info-box-content">
                    <div class="info-box-text">Admins Online Now</div>
                    <div class="info-box-number">{{ number_format($adminsOnlineNow) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-red">
                    <i class="fal fa-user-friends"></i>
                </span>
                <div class="info-box-content">
                    <div class="info-box-text">Total Admins</div>
                    <div class="info-box-number">{{ number_format($totalAdmins) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-blue">
                    <i class="fal fa-hat-cowboy"></i>
                </span>
                <div class="info-box-content">
                    <div class="info-box-text">Total Items</div>
                    <div class="info-box-number">{{ number_format($totalItems) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="info-box">
                <span class="info-box-icon bg-purple">
                    <i class="fal fa-group"></i>
                </span>
                <div class="info-box-content">
                    <div class="info-box-text">Total Groups</div>
                    <div class="info-box-number">{{ number_format($totalGroups) }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
