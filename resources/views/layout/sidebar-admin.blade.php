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
    use App\Models\Item;
    use App\Models\User;
    use App\Models\Group;
    use App\Models\Report;

    $pendingItemsCount = Item::where('status', '=', 'pending')->count();
    $pendingReportsCount = Report::where('seen', '=', false)->count();

    $totalItemsCount = Item::all()->count();
    $totalUsersCount = User::all()->count();
    $totalGroupsCount = Group::all()->count();

    $totalAdminsCount = User::where('power', '>', 0)->count();
@endphp

<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ storage(Auth::user()->headshot_url) }}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><a href="{{ route('admin.users', ['search' => Auth::user()->username]) }}" style="color:#fff;">{{ Auth::user()->username }}</a></p>
                <a>{{ Auth::user()->adminRank() }}</a>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @if (Auth::user()->power != 2)
                <li class="header">CREATIVE</li>
                <li>
                    <a href="{{ route('creator-area.create', ['type' => 'hat']) }}">
                        <i class="fas fa-hard-hat"></i>
                        <span>Create Hat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('creator-area.create', ['type' => 'face']) }}">
                        <i class="fas fa-smile"></i>
                        <span>Create Face</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('creator-area.create', ['type' => 'accessory']) }}">
                        <i class="fas fa-sword"></i>
                        <span>Create Accessory</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('creator-area.create', ['type' => 'head']) }}">
                        <i class="fa fa-user"></i>
                        <span>Create Head</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('creator-area.create', ['type' => 'set']) }}">
                        <i class="fa fa-object-group"></i>
                        <span>Create Set</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->power >= 2)
                <li class="header">MODERATION</li>
                <li>
                    <a href="{{ route('admin.asset_approval') }}">
                        <i class="fa fa-image"></i>
                        <span>Pending Assets</span>
                        @if ($pendingItemsCount > 0)
                            <span class="pull-right-container">
                                <span class="label label-danger pull-right">{{ $pendingItemsCount }}</span>
                            </span>
                        @endif
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-shield"></i>
                        <span>Pending Group Icons</span>
                        <span class="pull-right-container">
                            <span class="label label-danger pull-right">N/A</span>
                        </span>
                    </a>
                </li> --}}
                <li>
                    <a href="{{ route('admin.reports') }}">
                        <i class="fa fa-flag"></i>
                        <span>User Reports</span>
                        @if ($pendingReportsCount > 0)
                            <span class="pull-right-container">
                                <span class="label label-danger pull-right">{{ $pendingReportsCount }}</span>
                            </span>
                        @endif
                    </a>
                </li>
                <li class="header">SEARCH</li>
                <li>
                    <a href="{{ route('admin.items') }}">
                        <i class="fa fa-file-image"></i>
                        <span>Items</span>
                        <span class="pull-right-container">
                            <span class="label label-primary pull-right">{{ $totalItemsCount }}</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}">
                        <i class="fa fa-user"></i>
                        <span>Users</span>
                        <span class="pull-right-container">
                            <span class="label label-primary pull-right">{{ $totalUsersCount }}</span>
                        </span>
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('admin.groups') }}">
                        <i class="fa fa-group"></i>
                        <span>Groups</span>
                        <span class="pull-right-container">
                            <span class="label label-primary pull-right">{{ $totalGroupsCount }}</span>
                        </span>
                    </a>
                </li> --}}
            @endif
            @if (Auth::user()->power >= 4)
                <li class="header">MANAGEMENT</li>
                <li>
                    <a href="{{ route('admin.site_settings') }}">
                        <i class="fa fa-cog"></i>
                        <span>Site Settings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.manage_staff') }}">
                        <i class="fa fa-gavel"></i>
                        <span>Manage Staff</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.staff') }}">
                        <i class="fa fa-user"></i>
                        <span>View Staff</span>
                        <span class="pull-right-container">
                            <span class="label label-primary pull-right">{{ $totalAdminsCount }}</span>
                        </span>
                    </a>
                </li>
            @endif
        </ul>
    </section>
</aside>
