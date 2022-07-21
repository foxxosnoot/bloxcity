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
    'pageTitle' => 'Ban ' . $user->username
])

@section('header')
    <section class="content-header">
        <h1>
            Ban User
            <small>Send 'em to banland</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Ban User</li>
        </ol>
    </section>
@endsection

@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Ban {{ $user->username }}</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('admin.ban.store') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <label class="form-label">Length</label>
                <select class="form-control" name="length" required>
                    <option value="warning" selected>Warning</option>
                    <option value="12_hours">12 Hours</option>
                    <option value="1_day">1 Day</option>
                    <option value="3_days">3 Days</option>
                    <option value="7_days">7 Days</option>
                    <option value="14_days">14 Days</option>
                    <option value="closed">Close Account</option>
                </select>
                <label class="form-label">Category</label>
                <select class="form-control" name="category" required>
                    <option value="spam" selected>Spam</option>
                    <option value="profanity">Profanity</option>
                    <option value="sensitive_topics">Sensitive Topics</option>
                    <option value="offsite_links">Offsite Links</option>
                    <option value="harassment">Harassment</option>
                    <option value="discrimination">Discrimination</option>
                    <option value="sexual_content">Sexual Content</option>
                    <option value="inappropriate_content">Inappropriate Content</option>
                    <option value="false_information">Spreading False Information</option>
                    <option value="other">Other</option>
                </select>
                <label class="form-label">Note (optional)</label>
                <textarea class="form-control" name="note" placeholder="Note" rows="4"></textarea>
                <button class="btn btn-danger" type="submit">Ban User</button>
            </form>
        </div>
    </div>
@endsection
