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
    'pageTitle' => 'Settings',
    'bodyClass' => 'settings-page',
    'gridClass' => 'settings-grid'
])

@section('additional_js')
    <script>
        var currentTab = 'account';

        $(function() {
            $('.tab-link').click(function(tab) {
                $(`#${currentTab}_tab`).removeClass('active');
                $(`#${tab.target.id}`).addClass('active');

                $(`#${currentTab}`).hide();

                currentTab = tab.target.id.replace('_tab', '');

                $(`#${currentTab}`).show();
            });
        });
    </script>
@endsection

@section('content')
    @if (!settings('settings_enabled'))
        <div class="container construction-container">
            <i class="icon icon-sad construction-icon"></i>
            <div class="construction-text">Sorry, Account Settings are unavailable right now for maintenance. Try again later.</div>
        </div>
    @else
        <div class="tabs">
            <div class="tab">
                <a class="tab-link active" id="account_tab">Account</a>
            </div>
            <div class="tab">
                <a class="tab-link" id="privacy_tab">Privacy & Blocked</a>
            </div>
            <div class="tab">
                <a class="tab-link" id="password_tab">Password</a>
            </div>
            <div class="tab">
                <a class="tab-link" id="billing_tab">Billing</a>
            </div>
        </div>
        <div class="container" id="account">
            <div class="settings-title">Account</div>
            <form action="{{ route('account.settings.update') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="setting" value="account">
                <div class="setting">
                    <div class="setting-name">User ID</div>
                    <div class="setting-result">{{ number_format(Auth::user()->id) }}</div>
                </div>
                <div class="setting">
                    <div class="setting-name">Username</div>
                    <div class="setting-result">
                        <input class="form-input" type="text" name="username" placeholder="Username" value="{{ Auth::user()->username }}">
                    </div>
                    <div class="setting-description">Changing your username costs <span class="text-cash">$250 Cash</span>.</div>
                </div>
                <div class="setting">
                    <div class="setting-name">Email</div>
                    <div class="setting-result">
                        <input class="form-input" type="email" name="email" placeholder="Email" value="{{ Auth::user()->email }}" disabled>
                    </div>
                    <div class="setting-description">Your email helps keep your account secure.</div>
                </div>
                <div class="setting">
                    <div class="setting-name">Theme</div>
                    <div class="setting-result">
                        <select class="form-input" name="theme">
                            <option value="light" @if (Auth::user()->theme == 'light') selected @endif>Light</option>
                            <option value="dark" @if (Auth::user()->theme == 'dark') selected @endif>Dark</option>
                        </select>
                    </div>
                </div>
                <div class="push-15"></div>
                <div class="settings-title">Blurb <div class="settings-title-extra">(1,000 characters maximum)</div></div>
                <textarea class="form-input" name="description" placeholder="Description" rows="5" length="100">{{ Auth::user()->description }}</textarea>
                <div class="push-15"></div>
                <div class="settings-title">Forum Signature <div class="settings-title-extra">(100 characters maximum)</div></div>
                <input class="form-input" name="signature" placeholder="Signature" length="100" value="{{ Auth::user()->signature }}">
                <div class="text-right">
                    <button class="button settings-button button-blue" type="submit">Update Account</button>
                </div>
            </form>
        </div>
        <div class="container" id="privacy" style="display:none;">
            <div class="settings-title">Privacy</div>
            <form action="{{ route('account.settings.update') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="setting" value="privacy">
                <div class="setting">
                    <div class="setting-name">Who can message me?</div>
                    <div class="setting-result">
                        <select class="form-input" name="message">
                            <option value="everyone" @if (Auth::user()->setting_message == 'everyone') selected @endif>Everyone</option>
                            {{-- <option value="friends" @if (Auth::user()->setting_message == 'friends') selected @endif>Friends Only</option> --}}
                            <option value="no_one" @if (Auth::user()->setting_message == 'no_one') selected @endif>No One</option>
                        </select>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-name">Who can friend me?</div>
                    <div class="setting-result">
                        <select class="form-input" name="friend">
                            <option value="everyone" @if (Auth::user()->setting_friend == 'everyone') selected @endif>Everyone</option>
                            <option value="no_one" @if (Auth::user()->setting_friend == 'no_one') selected @endif>No One</option>
                        </select>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-name">Who can send me trades?</div>
                    <div class="setting-result">
                        <select class="form-input" name="trade">
                            <option value="everyone" @if (Auth::user()->setting_trade == 'everyone') selected @endif>Everyone</option>
                            {{-- <option value="friends" @if (Auth::user()->setting_trade == 'friends') selected @endif>Friends Only</option> --}}
                            <option value="no_one" @if (Auth::user()->setting_trade == 'no_one') selected @endif>No One</option>
                        </select>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-name">Who can view my items?</div>
                    <div class="setting-result">
                        <select class="form-input" name="inventory">
                            <option value="everyone" @if (Auth::user()->setting_inventory == 'everyone') selected @endif>Everyone</option>
                            {{-- <option value="friends" @if (Auth::user()->setting_inventory == 'friends') selected @endif>Friends Only</option> --}}
                            <option value="no_one" @if (Auth::user()->setting_inventory == 'no_one') selected @endif>No One</option>
                        </select>
                    </div>
                </div>
                <div class="text-right">
                    <button class="button settings-button button-blue" type="submit">Update Privacy</button>
                </div>
            </form>
            <div class="push-25"></div>
            <div class="settings-title">Blocked Users</div>
            <p>Coming soon!</p>
        </div>
        <div class="container" id="password" style="display:none;">
            <div class="settings-title">Password</div>
            <form action="{{ route('account.settings.update') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="setting" value="password">
                <div class="setting">
                    <div class="setting-name">Current Password</div>
                    <div class="setting-result">
                        <input class="form-input" type="password" name="current_password" placeholder="Current Password" required>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-name">New Password</div>
                    <div class="setting-result">
                        <input class="form-input" type="password" name="new_password" placeholder="New Password" required>
                    </div>
                </div>
                <div class="setting">
                    <div class="setting-name">New Password (Again)</div>
                    <div class="setting-result">
                        <input class="form-input" type="password" name="new_password_confirmation" placeholder="New Password (Again)" required>
                    </div>
                </div>
                <div class="text-right">
                    <button class="button settings-button button-blue" type="submit">Update Password</button>
                </div>
            </form>
        </div>
        <div class="container" id="billing" style="display:none;">
            <div class="settings-title">Billing</div>
            @if (Auth::user()->vip == 0)
                <p>You have no active VIP subscription. <a href="{{ route('upgrade.index') }}" target="_blank">Click here</a> to upgrade!</p>
            @else
                <p>You currently have an active <span style="color:{{ Auth::user()->membershipColor() }};">{{ Auth::user()->membershipLevel() }}</span> subscription until {{ Auth::user()->vip_until }}.</p>
                <p>You can contact {!! mailto(config('blox.emails.support')) !!} for billing help.</p>
            @endif
        </div>
    @endif
@endsection
