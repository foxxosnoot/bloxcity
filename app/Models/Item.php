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

use App\Models\Comment;
use App\Models\SetItem;
use App\Models\Reseller;
use App\Models\Inventory;
use App\Models\ItemFavorites;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'price_coins',
        'price_cash',
        'public_view',
        'onsale',
        'type',
        'status',
        'collectible',
        'collectible_stock',
        'collectible_stock_original'
    ];

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'creator_id');
    }

    public function thumbnail()
    {
        switch ($this->status) {
            case 'pending':
                return config('blox.thumbnails.pending');
                break;
            case 'declined':
                return config('blox.thumbnails.declined');
                break;
            case 'accepted':
                return storage($this->thumbnail_url);
                break;
        }
    }

    public function type()
    {
        switch ($this->type) {
            case 1:
                return 'Hat';
                break;
            case 2:
                return 'Face';
                break;
            case 3:
                return 'Accessory';
                break;
            case 4:
                return 'T-Shirt';
                break;
            case 5:
                return 'Shirt';
                break;
            case 6:
                return 'Pants';
                break;
            case 7:
                return 'Head';
                break;
            case 8:
                return 'Set';
                break;
        }
    }

    public function ownerCount()
    {
        return Inventory::where('item_id', '=', $this->id)->count();
    }

    public function favoriteCount()
    {
        return ItemFavorites::where('item_id', '=', $this->id)->count();
    }

    public static function checkOwnership($itemId, $userId)
    {
        return (boolean) (Inventory::where([
            ['item_id', '=', $itemId],
            ['user_id', '=', $userId]
        ])->exists());
    }

    public static function grant($itemId, $userId)
    {
        return Inventory::create([
            'item_id' => $itemId,
            'user_id' => $userId
        ]);
    }

    public static function ownsItemInSet($setId, $userId)
    {
        $setItems = SetItem::where('set_id', '=', $setId)->get();

        foreach ($setItems as $item) {
            if (self::checkOwnership($item->item_id, $userId)) {
                return true;
            }
        }

        return false;
    }

    public static function grantSetItems($setId, $userId)
    {
        $setItems = SetItem::where('set_id', '=', $setId)->get();

        foreach ($setItems as $item) {
            if (!self::checkOwnership($item->item_id, $userId)) {
                self::grant($item->item_id, $userId);
            }
        }
    }

    public function setItems()
    {
        $setItems = SetItem::where('set_id', '=', $this->id)->get();
        $items = [];

        foreach ($setItems as $item) {
            $items[] = $item->item_id;
        }

        return $this->whereIn('id', $items)->get();
    }

    public static function ungrant($itemId, $userId)
    {
        return Inventory::where([
            'item_id' => $itemId,
            'user_id' => $userId
        ])->delete();
    }

    public function favorite($userId)
    {
        return ItemFavorites::create([
            'item_id' => $this->id,
            'user_id' => $userId
        ]);
    }

    public function unfavorite($userId)
    {
        return ItemFavorites::where([
            'item_id' => $this->id,
            'user_id' => $userId
        ])->delete();
    }

    public function scrubName()
    {
        $this->timestamps = false;
        $this->name = '[Content Removed]';
        $this->save();
    }

    public function scrubDescription()
    {
        $this->timestamps = false;
        $this->description = '[Content Removed]';
        $this->save();
    }

    public function comments()
    {
        return Comment::where('item_id', '=', $this->id)->orderBy('created_at', 'DESC')->paginate(10);
    }

    public function resellers()
    {
        return Reseller::where('item_id', '=', $this->id)->orderBy('price', 'DESC')->get();
    }

    public function notifyDiscord()
    {
        if (config('blox.item_notifier.enabled')) {
            $name = config('app.name');
            $content = (!$this->collectible) ? 'New item!' : 'New collectible!';

            Http::post(config('blox.item_notifier.url'), [
                'content' => '@everyone ' . $content,
                'embeds' => [
                    [
                        'title' => $this->name,
                        'url' => route('market.show', ['id' => $this->id]),
                        'description' => "[View on {$name}](" . route('market.show', ['id' => $this->id]) . ")",
                        'thumbnail' => [
                            'url' => $this->thumbnail()
                        ]
                    ]
                ]
            ]);
        }
    }
}
