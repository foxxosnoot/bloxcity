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
    $positions = [];
@endphp

<div class="grid-x grid-margin-x">
    @forelse ($positions as $position)
        <div class="cell medium-4">
            <div class="container mb-15" style="padding:5px 15px;padding-top:20px;padding-bottom:20px;text-align:center;">
                <div style="font-size:20px;"><strong>{{ $position['title'] }}</strong></div>
                <div style="font-size:18px;opacity:.8;"><strong>{{ $position['amount'] }} open {{ ($position['amount'] == 1) ? 'position' : 'positions' }}</strong></div>
            </div>
        </div>
    @empty
        <div class="cell auto">There are currently no open positions. Check back soon! :-)</div>
        <div class="push-15"></div>
    @endforelse
</div>
<div><small style="font-style:italic;">Currently all positions require you work voluntarily from home.</small></div>
<hr>
<p>Please email {!! mailto(config('blox.emails.careers')) !!} with job title and a resume to apply.</p>
<p>You must have no prior bans longer than 1 day and be at least 16 years old to apply.</p>
<p>If you are unsure how to apply, please contact an Administrator.</p>
