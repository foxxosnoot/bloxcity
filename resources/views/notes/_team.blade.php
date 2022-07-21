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
    use App\Models\User;

    $teamMembers = User::where([['power', '>', 0] ,['id', '!=', 1]])->orderBy('id', 'ASC')->get();
@endphp

@forelse ($teamMembers as $teamMember)
    @php
        switch ($teamMember->power) {
            case 1:
                $rank = 'Asset Creator';
                $class = 'asset';
                break;
            case 2:
                $rank = 'Moderator';
                $class = 'moderator';
                break;
            case 3:
            case 4:
            case 5:
                $rank = 'Administrator';
                $class = 'administrator';
                break;
        }
    @endphp

    <div class="grid-x grid-margin-x team-member">
        <div class="cell small-4 medium-3">
            <a href="{{ route('users.profile', ['username' => $teamMember->username]) }}">
                <img class="team-member-avatar" src="{{ storage($teamMember->avatar_url) }}">
            </a>
        </div>
        <div class="cell small-8 medium-9">
            <a href="{{ route('users.profile', ['username' => $teamMember->username]) }}" class="team-member-username">{{ $teamMember->username }}</a>
            <div class="team-member-rank rank-{{ $class }}"><i class="fas fa-gavel"></i> {{ $rank }}</div>
            <div class="team-member-description">{{ $teamMember->description ?? 'This team member has no description.' }}</div>
        </div>
    </div>
@empty
    <p>There are currently no team members.</p>
@endforelse
