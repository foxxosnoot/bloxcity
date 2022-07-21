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

use Carbon\Carbon;
use App\Models\Ban;
use App\Models\Item;
use App\Models\Friend;
use App\Models\Status;
use App\Models\ForumReply;
use App\Models\ForumThread;
use Illuminate\Support\Str;
use App\Models\ItemFavorites;
use App\Models\UsernameHistory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'ip',
        'description',
        'currency_coins',
        'currency_cash',
        'next_currency_payout',
        'banned',
        'vip',
        'verified',
        'beta_tester',
        'power',
        'rgb_head',
        'rgb_torso',
        'rgb_left_arm',
        'rgb_right_arm',
        'rgb_left_leg',
        'rgb_right_leg',
        'hat1',
        'hat2',
        'hat3',
        'face',
        'accessory',
        'tshirt',
        'shirt',
        'pants',
        'head',
        'flood'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function online()
    {
        return (strtotime($this->updated_at) + 300) > time();
    }

    public function flooding()
    {
        return time() < strtotime($this->flood);
    }

    public function updateFlood()
    {
        $this->timestamps = false;
        $this->flood = Carbon::createFromTimestamp(time() + config('blox.flood_time'))->toDateTimeString();
        $this->save();
    }

    public function postCount()
    {
        $threads = ForumThread::where('creator_id', '=', $this->id)->count();
        $replies = ForumReply::where('creator_id', '=', $this->id)->count();

        return $threads + $replies;
    }

    public function itemCount()
    {
        return Inventory::where('user_id', '=', $this->id)->count();
    }

    public function achievements()
    {
        $achievements = [];

        if ($this->power > 0) {
            $achievements[] = config('blox.achievements')['admin'];
        }

        if ($this->id == 305) {
            $achievements['endorsed'] = config('blox.achievements')['endorsed'];
            $achievements['endorsed']['name'] = str_replace(' Player', '', $achievements['endorsed']['name']);
        }

        if ($this->id == 305 || $this->id == 265) {
            $achievements[] = config('blox.achievements')['asset_helper'];
        }

        if ($this->beta_tester) {
            $achievements[] = config('blox.achievements')['beta'];
        }

        if ($this->vip > 0) {
            $achievements['vip'] = config('blox.achievements')['VIP_' . $this->vip];
            $achievements['vip']['name'] = str_replace(' Membership', '', $achievements['vip']['name']);
        }

        if ($this->currency_cash >= 5000) {
            $achievements[] = config('blox.achievements')['rich'];
        }

        if (strtotime($this->created_at) <= (time() - 31536000)) {
            $achievements[] = config('blox.achievements')['veteran'];
        }

        if ($this->postCount() >= 500) {
            $achievements[] = config('blox.achievements')['forumer'];
        }

        if ($this->itemCount() >= 30) {
            $achievements[] = config('blox.achievements')['stockpiler'];
        }

        return $achievements;
    }

    public function membershipLevel()
    {
        switch ($this->vip) {
            case 0:
                return 'None';
                break;
            case 1:
                return 'Bronze VIP';
                break;
            case 2:
                return 'Silver VIP';
                break;
            case 3:
                return 'Gold VIP';
                break;
            case 4:
                return 'Platinum VIP';
                break;
        }
    }

    public function membershipColor()
    {
        switch ($this->vip) {
            case 0:
                return 'inherit';
                break;
            case 1:
                return '#6d4c41';
                break;
            case 2:
                return '#9e9e9e';
                break;
            case 3:
                return '#fbc02d';
                break;
            case 4:
                return '#616161';
                break;
        }
    }

    public function adminRank()
    {
        if ($this->id == 1) {
            return 'System';
        }

        switch ($this->power) {
            case 1:
                return 'Asset Creator';
                break;
            case 2:
                return 'Moderator';
                break;
            case 3:
                return 'Administrator';
                break;
            case 4:
                return 'Executive Administrator';
                break;
            case 5:
                return 'System';
                break;
        }
    }

    public function createdItems()
    {
        return Item::where('creator_id', '=', $this->id);
    }

    public function resetAvatar()
    {
        if ($this->avatar_url != 'img/avatars/BUHuVgds3QM869RpKrjLcIhNx.png') {
            deleteAsset($this->avatar_url);
        }

        if ($this->headshot_url != 'img/headshots/BUHuVgds3QM869RpKrjLcIhNx.png') {
            deleteAsset($this->headshot_url);
        }

        $this->timestamps = false;
        $this->avatar_url = 'img/avatars/BUHuVgds3QM869RpKrjLcIhNx.png';
        $this->headshot_url = 'img/headshots/BUHuVgds3QM869RpKrjLcIhNx.png';
        $this->rgb_head = '255/255,255/255,255/255';
        $this->rgb_torso = '125/255,125/255,125/255';
        $this->rgb_left_arm = '255/255,255/255,255/255';
        $this->rgb_right_arm = '255/255,255/255,255/255';
        $this->rgb_left_leg = '255/255,255/255,255/255';
        $this->rgb_right_leg = '255/255,255/255,255/255';
        $this->hat1 = null;
        $this->hat2 = null;
        $this->hat3 = null;
        $this->face = null;
        $this->accessory = null;
        $this->tshirt = null;
        $this->shirt = null;
        $this->pants = null;
        $this->head = null;
        $this->save();
    }

    public function scrubUsername()
    {
        $contentID = mt_rand(100000, 999999);

        $colors = [
            'Red',
            'Blue',
            'Green',
            'Black',
            'White',
            'Yellow',
            'Orange',
            'Pink'
        ];

        $animals = [
            'Puppy',
            'Giraffe',
            'Zebra',
            'Kitten',
            'Tiger',
            'Lion',
            'Unicorn',
            'Dolphin',
            'Shark',
            'Whale',
            'Snake',
            'Dinosaur',
            'Fish',
            'Eagle',
            'Rooster',
            'Wolf',
            'Hippo',
            'Horse',
            'Turtle',
            'Squirrel'
        ];

        shuffle($colors);
        shuffle($animals);

        $this->timestamps = false;
        $this->username = $colors[0] . $animals[0] . $contentID;
        $this->save();
    }

    public function scrubDescription()
    {
        $this->timestamps = false;
        $this->description = '[Content Removed]';
        $this->save();
    }

    public function scrubSignature()
    {
        $this->timestamps = false;
        $this->signature = '[Content Removed]';
        $this->save();
    }

    public function generateDiscordCode()
    {
        $this->timestamps = false;
        $this->discord_code = Str::random(40);
        $this->save();
    }

    public function forumBanner()
    {
        if ($this->power > 0) {
            $image = config('blox.forum_banners.admin');
        } else if ($this->vip > 0) {
            $image = config('blox.forum_banners.VIP_'. $this->vip);
        }

        if (isset($image)) {
            return '<img class="forum-thread-banner" src="'. $image .'">';
        }
    }

    public function hasFavoritedItem($itemId)
    {
        return ItemFavorites::where([
            'item_id' => $itemId,
            'user_id' => $this->id
        ])->exists();
    }

    public function taxFloor()
    {
        return config('blox.tax_floors.VIP_'. $this->vip);
    }

    public function dailyCash()
    {
        return config('blox.daily_cash.VIP_'. $this->vip);
    }

    public function punishments()
    {
        return Ban::where('user_id', '=', $this->id)->orderBy('created_at', 'DESC')->get();
    }

    public function punishmentCount()
    {
        return Ban::where('user_id', '=', $this->id)->count();
    }

    public function usernameHistory()
    {
        return UsernameHistory::where('user_id', '=', $this->id)->orderBy('created_at', 'ASC')->get();
    }

    public function friendRequests()
    {
        return Friend::where([
            ['receiver_id', '=', $this->id],
            ['status', '=', 'pending']
        ])->orderBy('created_at', 'DESC')->get();
    }

    public function friendsOfMine()
{
        return $this->belongsToMany($this, 'friends', 'sender_id', 'receiver_id');
    }

    public function friendOf()
    {
        return $this->belongsToMany($this, 'friends', 'receiver_id', 'sender_id');
    }

    public function friends()
    {
        return $this->friendsOfMine()->wherePivot('status', '=', 'accepted')->orderBy('created_at', 'DESC')->get()->merge($this->friendOf()->wherePivot('status', '=', 'accepted')->orderBy('created_at', 'DESC')->get());
    }

    public function favoriteItems()
    {
        return ItemFavorites::where('user_id', '=', $this->id)->count();
    }

    public function status()
    {
        $status = Status::where('creator_id', '=', $this->id)->orderBy('created_at', 'DESC')->first();
        return $status->content ?? null;
    }
}
