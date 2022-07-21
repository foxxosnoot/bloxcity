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
    'pageTitle' => $title,
    'bodyClass' => 'upgrade-page',
    'gridFluid' => true
])

@section('content')
    <h5>Purchase {{ $title }}</h5>
    <div class="grid-x grid-margin-x mb-10">
        @forelse ($plans as $upgradePlan)
            <div class="cell small-6 medium-3">
                <div class="upgrade-header {{ $plan }}">{{ $upgradePlan['name'] }}</div>
                <div class="upgrade-title {{ $plan }}">
                    <div class="upgrade-title-price">{{ $upgradePlan['price'] }}</div>
                </div>
                <div class="upgrade-benefits">
                    <div class="upgrade-button-holder">
                        <form action="{{ route('upgrade.paypal') }}" method="POST">
                            {{ csrf_field() }}
                            <button class="upgrade-button {{ $plan }}" style="margin-top:0;">Buy Now</button>
                        </form>
                    </div>
                </div>
                <div class="push-15"></div>
            </div>
        @empty
            <div class="cell auto">There are currently no {{ $title }} plans available. Check again later.</div>
        @endforelse
    </div>
    @if ($plan != 'cash')
        <div class="grid-x grid-margin-x mb-25">
            <div class="cell medium-4 hide-for-small-only">
                <img class="upgrade-cash-avatar" src="{{ storage('web-img/error.png') }}">
            </div>
            <div class="cell medium-6">
                <div class="upgrade-cash-container">
                    <div class="upgrade-cash-title">Friendly Notice</div>
                    <div class="upgrade-cash-description">VIP Memberships do not automatically renew as of now. You will need to purchase it again if you wish to renew once it has expired.</div>
                </div>
            </div>
        </div>
    @endif
    <a href="{{ route('upgrade.index') }}">Return to Upgrades</a>
@endsection
