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

<nav class="main-header">
    <a href="{{ route('admin.dashboard') }}" class="logo">
        <span class="logo-mini"><img src="{{ storage('web-img/icon-admin.png') }}"></span>
        <span class="logo-lg"><img src="{{ storage('web-img/logo-admin.png') }}"></span>
    </a>
    <div class="navbar navbar-static-top">
        <a href="#" onclick="toggleSidebar()" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"><i class="fas fa-home"></i>&nbsp;&nbsp;Return to {{ config('app.name') }}</a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ storage(Auth::user()->headshot_url) }}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ Auth::user()->username }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ storage(Auth::user()->headshot_url) }}" class="img-circle" alt="User Image">
                            <p>
                                {{ Auth::user()->username }}
                                <small>{{ Auth::user()->adminRank() }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('users.profile', ['username' => Auth::user()->username]) }}" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('logout') }}" class="btn btn-default btn-flat">Logout</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
