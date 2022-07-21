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

@if ($paginator->hasPages())
    <ul class="pagination">
        @if ($paginator->onFirstPage())
            <li disabled><span><i class="icon icon-paginate-previous"></i></span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}"><i class="icon icon-paginate-previous"></i></a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li disabled><span>{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if (!$paginator->hasMorePages())
            <li disabled><span><i class="icon icon-paginate-next"></i></span></li>
        @else
            <li><a href="{{ $paginator->nextPageUrl() }}"><i class="icon icon-paginate-next"></i></a></li>
        @endif
    </ul>
@endif
