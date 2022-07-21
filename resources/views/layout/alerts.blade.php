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

@if (settings('maintenance_enabled') && session()->has('maintenance_code'))
    <div class="alert alert-error">
        <div class="grid-x grid-margin-x align-middle">
            <div class="cell shrink left">
                <i class="icon icon-error"></i>
            </div>
            <div class="cell auto text-center">
                You are in maintenance mode.
                &nbsp;&nbsp;
                <a href="{{ route('maintenance.exit') }}" class="button button-green" style="font-size:11px;">Exit</a>
            </div>
            <div class="cell shrink right">
                <i class="icon icon-error"></i>
            </div>
        </div>
    </div>
@endif

@if (count($errors) > 0)
    <div class="alert alert-error">
        @foreach ($errors->all() as $error)
            <div>{!! $error !!}</div>
        @endforeach
    </div>
@endif

@if (session()->has('success_message'))
    <div class="alert alert-success">
        {!! session()->get('success_message') !!}
    </div>
@endif
