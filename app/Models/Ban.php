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

class Ban extends Model
{
    use HasFactory;

    protected $table = 'bans';

    protected $fillable = [
        'user_id',
        'mod_id',
        'category',
        'content',
        'note',
        'length',
        'banned_until'
    ];

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'mod_id');
    }

    public function category()
    {
        switch ($this->category) {
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

    public function length()
    {
        switch ($this->length) {
            case 'warning':
                return 'Warning';
                break;
            case '12_hours':
                return '12 Hours';
                break;
            case '1_day':
                return '1 Day';
                break;
            case '3_days':
                return '3 Days';
                break;
            case '7_days':
                return '7 Days';
                break;
            case '14_days':
                return '14 Days';
                break;
            case 'closed':
                return 'Account Closed';
                break;
        }
    }
}
