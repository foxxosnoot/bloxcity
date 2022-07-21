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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/js/foundation.min.js"></script>
<script>
    $(document).ready(function() {
        $(document).foundation();

        let data = $('meta[name="user-data"]');

        if (data.attr('data-authenticated') == 'false') {
            window.userData = {
                authenticated: false,
                csrf: $('meta[name="csrf-token"]').attr('content')
            };
        } else {
            window.userData = {
                authenticated: true,
                csrf: $('meta[name="csrf-token"]').attr('content'),
                id: parseInt(data.attr('data-id')),
                username: data.attr('data-username'),
                vip: parseInt(data.attr('data-vip')),
                coins: parseInt(data.attr('data-coins')),
                cash: parseInt(data.attr('data-cash')),
                angle: data.attr('data-angle')
            };
        }

        var sidebar = false;

        $('#sidebarToggler').click(function() {
            sidebar = !sidebar;

            if (sidebar) {
                $('.sidebar').removeClass('hide');
            }
            else {
                $('.sidebar').addClass('hide');
            }
        });
    });
</script>
<script src="{{ asset('js/dropdown-menu.js?v=3') }}"></script>
@yield('additional_js')
