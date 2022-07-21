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

use App\Models\ForumReply;
use App\Models\ForumTopic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForumThread extends Model
{
    use HasFactory;

    protected $table = 'forum_threads';

    protected $fillable = [
        'topic_id',
        'creator_id',
        'title',
        'body',
        'last_reply_id',
        'last_poster_id'
    ];

    public function topic()
    {
        return ForumTopic::where('id', '=', $this->topic_id)->first();
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }

    public function replies()
    {
        if (Auth::check() && Auth::user()->power > 1) {
            return $this->hasMany('App\Models\ForumReply', 'thread_id');
        }

        return $this->hasMany('App\Models\ForumReply', 'thread_id')->where('deleted', '=', false);
    }

    public function lastReply()
    {
        return $this->belongsTo('App\Models\ForumReply', 'last_reply_id');
    }

    public function lastPoster()
    {
        return $this->belongsTo('App\Models\User', 'last_poster_id');
    }

    public function scrubTitle()
    {
        $this->timestamps = false;
        $this->title = '[Content Removed]';
        $this->save();
    }

    public function scrubBody()
    {
        $this->timestamps = false;
        $this->body = '[Content Removed]';
        $this->save();
    }
}
