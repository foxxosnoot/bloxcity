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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'user_id',
        'content_id',
        'type',
        'reason',
        'comment'
    ];

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function url()
    {
        switch ($this->type) {
            case 'user':
                return route('users.profile', ['username' => $this->content->username]);
                break;
            case 'item':
                return route('market.show', ['id' => $this->content_id]);
                break;
            case 'forum-thread':
                return route('forum.thread', ['id' => $this->content_id]);
                break;
            case 'forum-reply':
                return route('forum.thread', ['id' => $this->content->thread_id]) . '#reply_' . $this->content_id;
                break;
            case 'item-comment':
                return route('market.show', ['id' => $this->content->item->id]);
                break;
        }
    }

    public function type()
    {
        switch ($this->type) {
            case 'user':
                return 'User';
                break;
            case 'item':
                return 'Item';
                break;
            case 'forum-thread':
                return 'Forum Thread';
                break;
            case 'forum-reply':
                return 'Forum Reply';
                break;
            case 'item-comment':
                return 'Item Comment';
                break;
        }
    }

    public function content()
    {
        switch ($this->type) {
            case 'user':
                return $this->belongsTo('App\Models\User', 'content_id');
                break;
            case 'item':
                return $this->belongsTo('App\Models\Item', 'content_id');
                break;
            case 'forum-thread':
                return $this->belongsTo('App\Models\ForumThread', 'content_id');
                break;
            case 'forum-reply':
                return $this->belongsTo('App\Models\ForumReply', 'content_id');
                break;
            case 'item-comment':
                return $this->belongsTo('App\Models\Comment', 'content_id');
                break;
        }
    }

    public function reason()
    {
        switch ($this->reason) {
            case 'spam':
                return 'Spam';
                break;
            case 'profanity':
                return 'Profanity';
                break;
            case 'sensitive_topics':
                return 'Sensitive Topics';
                break;
            case 'offsite_links':
                return 'Offsite Links';
                break;
            case 'harassment':
                return 'Harassment';
                break;
            case 'discrimination':
                return 'Discrimination';
                break;
            case 'sexual_content':
                return 'Sexual Content';
                break;
            case 'inappropriate_content':
                return 'Inappropriate Content';
                break;
            case 'false_information':
                return 'Spreading False Information';
                break;
            default:
                return 'Other';
                break;
        }
    }
}
