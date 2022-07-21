<?php
/**
 * MIT License
 *
 * Copyright (c) 2021-2022 FoxxoSnoot
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForumTopic extends Model
{
    use HasFactory;

    const UPDATED_AT = 'last_post_at';

    protected $table = 'forum_topics';

    protected $fillable = [
        'last_thread_id',
        'last_poster_id'
    ];

    public function threads()
    {
        if (Auth::check() && Auth::user()->power > 1) {
            return $this->hasMany('App\Models\ForumThread', 'topic_id');
        }

        return $this->hasMany('App\Models\ForumThread', 'topic_id')->where('deleted', '=', false);
    }

    public function replies()
    {
        return $this->hasMany('App\Models\ForumReply');
    }

    public function lastThread()
    {
        return $this->belongsTo('App\Models\ForumThread', 'last_thread_id');
    }

    public function lastPoster()
    {
        return $this->belongsTo('App\Models\User', 'last_poster_id');
    }
}
