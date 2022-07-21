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

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Comment;
use App\Models\Reseller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\ItemFavorites;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MarketController extends Controller
{
    public function index()
    {
        $categoryQueries = [ // I KNOW THIS IS BAD , but i'm lazy atm
            1 => Item::where([
                ['creator_id', '=', 1],
                ['public_view', '=', true],
                ['status', '=', 'accepted']
            ])->orderBy('updated_at', 'DESC')->take(50)->get(),
            2 => Item::where([
                ['creator_id', '=', 1],
                ['public_view', '=', true],
                ['name', 'LIKE', '%hair%'],
                ['status', '=', 'accepted']
            ])->orderBy('updated_at', 'DESC')->take(50)->get(),
            3 => Item::where([
                ['creator_id', '=', 1],
                ['type', '=', 1],
                ['public_view', '=', true],
                ['status', '=', 'accepted']
            ])->orderBy('updated_at', 'DESC')->take(50)->get(),
            4 => Item::where([
                ['creator_id', '=', 1],
                ['type', '=', 2],
                ['public_view', '=', true],
                ['status', '=', 'accepted']
            ])->orderBy('updated_at', 'DESC')->take(50)->get(),
            5 => Item::where([
                ['public_view', '=', true],
                ['status', '=', 'accepted']
            ])->whereIn('id', config('blox.featured_items'))->orderBy('updated_at', 'DESC')->take(50)->get(),
            6 => Item::where([
                ['creator_id', '=', 1],
                ['public_view', '=', true],
                ['name', 'LIKE', '%egg%'],
                ['status', '=', 'accepted']
            ])->orWhere([
                ['creator_id', '=', 1],
                ['public_view', '=', true],
                ['name', 'LIKE', '%bunny%'],
                ['status', '=', 'accepted']
            ])->orWhere([
                ['creator_id', '=', 1],
                ['public_view', '=', true],
                ['name', 'LIKE', '%yolk%'],
                ['status', '=', 'accepted']
            ])->orderBy('updated_at', 'DESC')->take(50)->get(),
        ];

        return view('market.index')->with([
            'categoryQueries' => $categoryQueries
        ]);
    }

    public function search()
    {
        return view('market.search');
    }

    public function show($id)
    {
        $item = Item::where([
            ['id', '=', $id]
        ])->with(['creator'])->firstOrFail();

        if ((!Auth::check() || (Auth::check() && Auth::user()->power == 0)) && !$item->public_view) {
            abort(404);
        }

        if (empty($item->description)) $item->description = 'This item has no description.';

        $owns = (Auth::check()) ? Item::checkOwnership($item->id, Auth::user()->id) : false;
        $buttonDisabled = ($owns || ($item->collectible && $item->collectible_stock == 0)) ? 'disabled' : '';
        $balanceAfterCoins = (Auth::check()) ? Auth::user()->currency_coins - $item->price_coins : 0;
        $balanceAfterCash = (Auth::check()) ? Auth::user()->currency_cash - $item->price_cash : 0;
        $creatorImage = ($item->creator->id == 1) ? storage('web-img/icon.png') : storage($item->creator->headshot_url);
        $suggestions = Item::where([['id', '!=', $item->id], ['type', '=', $item->type], ['public_view', '=', true], ['status', '=', 'accepted']])->take(4)->get();

        return view('market.show')->with([
            'item' => $item,
            'owns' => $owns,
            'buttonDisabled' => $buttonDisabled,
            'balanceAfterCoins' => $balanceAfterCoins,
            'balanceAfterCash' => $balanceAfterCash,
            'creatorImage' => $creatorImage,
            'suggestions' => $suggestions
        ]);
    }

    public function buy(Request $request)
    {
        if (!settings('market_purchases_enabled')) {
            return back()->withErrors(['Market purchases are currently disabled.']);
        }

        $isReseller = $request->has('reseller_id');
        $item = Item::where([
            ['id', '=', $request->id]
        ])->with(['creator'])->firstOrFail();

        if (Auth::user()->power == 0 && !$item->public_view) {
            abort(404);
        }

        $this->validate(request(), [
            'currency' => ['required', Rule::in(['coins', 'cash'])]
        ], [
            'currency.required' => 'You must provide a currency to pay with.'
        ]);

        if ($item->status != 'accepted') {
            return back()->withErrors(['This item is not accepted!']);
        }

        if (!$item->onsale) {
            return back()->withErrors(['This item is not for sale!']);
        }

        if (!$isReseller && $item->collectible && $item->collectible_stock <= 0) {
            return back()->withErrors(['This item is out of stock!']);
        }

        if (!$isReseller && Item::checkOwnership($item->id, Auth::user()->id)) {
            return back()->withErrors(['You already own this item!']);
        }

        if ($request->currency == 'coins') {
            if (Auth::user()->currency_coins < $item->price_coins) {
                return back()->withErrors(['You do not have enough Coins to purchase this item!']);
            }

            if ($item->price_coins <= 0 || $isReseller) {
                return back()->withErrors(['This item is not available for purchase with Coins!']);
            }

            $creator = $item->creator;
            $creator->timestamps = false;
            $creator->currency_coins += round(($item->price_coins / $item->creator->taxFloor()), 0, PHP_ROUND_HALF_UP);
            $creator->save();

            $myU = Auth::user();
            $myU->currency_coins -= $item->price_coins;
            $myU->save();

            if ($item->collectible) {
                $item->collectible_stock -= 1;
                $item->save();
            }

            if ($item->type == 8) {
                if (Item::ownsItemInSet($item->id, Auth::user()->id)) {
                    return back()->withErrors(['Oh no! You can\'t buy this set. It seems you own 1 or more items in this set!']);
                }

                Item::grantSetItems($item->id, Auth::user()->id);
            }

            Item::grant($item->id, Auth::user()->id);

            return back()->with('success_message', 'You have successfully purchased <strong>' . $item->name . '</strong> for ' . $item->price_coins . ' Coins.');
        } else if ($request->currency == 'cash') {
            if (!$isReseller) {
                if (Auth::user()->currency_cash < $item->price_cash) {
                    return back()->withErrors(['You do not have enough Cash to purchase this item!']);
                }

                if ($item->price_cash <= 0) {
                    return back()->withErrors(['This item is not available for purchase with Cash!']);
                }

                $creator = $item->creator;
                $creator->timestamps = false;
                $creator->currency_cash += round(($item->price_cash / $item->creator->taxFloor()), 0, PHP_ROUND_HALF_UP);
                $creator->save();

                $myU = Auth::user();
                $myU->currency_cash -= $item->price_cash;
                $myU->save();
                $myU->updateFlood();

                if ($item->collectible) {
                    $item->collectible_stock -= 1;
                    $item->save();
                }

                if ($item->type == 8) {
                    if (Item::ownsItemInSet($item->id, Auth::user()->id)) {
                        return back()->withErrors(['Oh no! You can\'t buy this set. It seems you own 1 or more items in this set!']);
                    }

                    Item::grantSetItems($item->id, Auth::user()->id);
                }

                Item::grant($item->id, Auth::user()->id);

                return back()->with('success_message', 'You have successfully purchased <strong>' . $item->name . '</strong> for ' . $item->price_cash . ' Cash.');
            } else {
                $reseller = Reseller::where('id', '=', $request->reseller_id)->firstOrFail();

                if ($reseller->seller->id == Auth::user()->id) {
                    return back()->withErrors(['You can not buy your own Collectible.']);
                }

                if (Auth::user()->currency_cash < $reseller->price) {
                    return back()->withErrors(['You do not have enough Cash to purchase this item!']);
                }

                $seller = $reseller->seller;
                $seller->currency_cash += round(($item->price_cash / $seller->taxFloor()), 0, PHP_ROUND_HALF_UP);
                $seller->save();

                $reseller->delete();

                $myU = Auth::user();
                $myU->currency_cash -= $reseller->price;
                $myU->save();

                $inventory = $reseller->inventory;
                $inventory->user_id = Auth::user()->id;
                $inventory->save();

                return back()->with('success_message', 'You have successfully purchased <strong>' . $item->name . '</strong> for ' . $reseller->price . ' Cash.');
            }
        }
    }

    public function resell(Request $request)
    {
        $item = Item::where([
            ['id', '=', $request->id]
        ])->firstOrFail();

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        $this->validate(request(), [
            'price' => ['required', 'numeric', 'min:1', 'max:1000000']
        ]);

        $reseller = Reseller::create([
            'seller_id' => Auth::user()->id,
            'item_id' => $item->id,
            'inventory_id' => 37,
            'price' => $request->price
        ]);

        Auth::user()->updateFlood();

        return back()->with('success_message', 'Collectible has been put up for sale.');
    }

    public function delete(Request $request)
    {
        $item = Item::where([
            ['id', '=', $request->id]
        ])->firstOrFail();

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        if ($item->type != 4 && $item->type != 5 && $item->type != 6) {
            return back()->withErrors(['You can only delete clothing from your inventory!']);
        }

        if ($item->collectible) {
            return back()->withErrors(['You can not delete Collectible items from your inventory!']);
        }

        $inventory = Inventory::where([['user_id', '=', Auth::user()->id], ['item_id', '=', $item->id]])->firstOrFail();
        $inventory->delete();

        Auth::user()->updateFlood();

        return back()->with('success_message', 'Item has been deleted from your inventory.');
    }

    public function favorite(Request $request)
    {
        $item = Item::where([
            ['id', '=', $request->id]
        ])->firstOrFail();

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        if (Auth::user()->hasFavoritedItem($request->id)) {
            $item->unfavorite(Auth::user()->id);

            Auth::user()->updateFlood();

            return back()->with('success_message', 'You have successfully unfavorited <strong>' . $item->name . '</strong>.');
        } else {
            $item->favorite(Auth::user()->id);

            Auth::user()->updateFlood();

            return back()->with('success_message', 'You have successfully favorited <strong>' . $item->name . '</strong>.');
        }
    }

    public function comment(Request $request)
    {
        $item = Item::where([
            ['id', '=', $request->item_id]
        ])->firstOrFail();

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        $this->validate(request(), [
            'body' => ['required', 'min:3', 'max:150']
        ]);

        if (isProfanity($request->body)) {
            return back()->withErrors(['One or more words in your comment triggered our profanity filter. Please update and try again.']);
        }

        $comment = Comment::create([
            'item_id' => $item->id,
            'creator_id' => Auth::user()->id,
            'body' => $request->body
        ]);

        Auth::user()->updateFlood();

        return back()->with('success_message', 'Comment has been posted!');
    }

    public function moderateComment(Request $request)
    {
        if (!isset($request->id) || Auth::user()->power < 2) {
            abort(404);
        }

        $comment = Comment::where('id', '=', $request->id)->firstOrFail();
        $comment->scrub();
        $comment->save();

        return back()->with('success_message', 'Comment scrubbed.');
    }

    public function edit($id)
    {
        $item = Item::where([
            ['id', '=', $id]
        ])->firstOrFail();

        if ($item->creator_id != Auth::user()->id && (Auth::user()->power == 0 || Auth::user()->power == 2)) {
            abort(404);
        }

        return view('market.edit')->with([
            'item' => $item
        ]);
    }

    public function editUpdate(Request $request)
    {
        $item = Item::where([
            ['id', '=', $request->id]
        ])->firstOrFail();

        if ($item->creator_id != Auth::user()->id && (Auth::user()->power == 0 || Auth::user()->power == 2)) {
            abort(404);
        }

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        if ($item->type == 1 || $item->type == 3) {
            $this->validate(request(), [
                'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/]+$/i'],
                'description' => ['max:1024'],
                'onsale' => ['required', 'numeric', 'min:0', 'max:1'],
                'price_coins' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'price_cash' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'public_view' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible' => ['required', 'numeric', 'min:0', 'max:1'],
                'image' => ['mimes:png,jpg,jpeg', 'max:2048'],
                'model' => ['mimes:txt', 'max:2048']
            ]);
        } else if ($item->type == 2) {
            $this->validate(request(), [
                'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/]+$/i'],
                'description' => ['max:1024'],
                'onsale' => ['required', 'numeric', 'min:0', 'max:1'],
                'price_coins' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'price_cash' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'public_view' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible' => ['required', 'numeric', 'min:0', 'max:1'],
                'image' => ['dimensions:min_width=512,min_height=512,max_width=512,max_height=512', 'mimes:png,jpg,jpeg', 'max:2048']
            ]);
        } else if ($item->type == 7) {
            $this->validate(request(), [
                'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/]+$/i'],
                'description' => ['max:1024'],
                'onsale' => ['required', 'numeric', 'min:0', 'max:1'],
                'price_coins' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'price_cash' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'public_view' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible' => ['required', 'numeric', 'min:0', 'max:1'],
                'model' => ['mimes:txt', 'max:2048']
            ]);
        } else if ($item->type == 8) {
            $this->validate(request(), [
                'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/]+$/i'],
                'description' => ['max:1024'],
                'onsale' => ['required', 'numeric', 'min:0', 'max:1'],
                'price_coins' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'price_cash' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'public_view' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible' => ['required', 'numeric', 'min:0', 'max:1'],
                'items' => ['required']
            ]);
        } else {
            if (Auth::user()->power == 1 || Auth::user()->power > 2) {
                $this->validate(request(), [
                    'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/]+$/i'],
                    'description' => ['max:1024'],
                    'onsale' => ['required', 'numeric', 'min:0', 'max:1'],
                    'price_coins' => ['required', 'numeric', 'min:0', 'max:1000000'],
                    'price_cash' => ['required', 'numeric', 'min:0', 'max:1000000'],
                    'image' => ['dimensions:min_width=887,min_height=887,max_width=887,max_height=887', 'mimes:png,jpg,jpeg', 'max:2048']
                ]);
            } else {
                $this->validate(request(), [
                    'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/]+$/i'],
                    'description' => ['max:1024'],
                    'onsale' => ['required', 'numeric', 'min:0', 'max:1'],
                    'price_coins' => ['required', 'numeric', 'min:0', 'max:1000000'],
                    'price_cash' => ['required', 'numeric', 'min:0', 'max:1000000']
                ]);
            }
        }

        if (isProfanity($request->name) || isProfanity($request->description)) {
            return back()->withErrors(['One or more words in your item name or description triggered our profanity filter. Please update and try again.']);
        }

        if ($request->onsale == 1 && $request->price_coins == 0 && $request->price_cash == 0) {
            return back()->withErrors(['You must sell this item for Coins or Cash.']);
        }

        if ($item->type == 1 || $item->type == 2 || $item->type == 3 || $item->type == 7 || $item->type == 8) {
            $item->name = $request->name;
            $item->description = $request->description;
            $item->onsale = $request->onsale;
            $item->price_coins = $request->price_coins;
            $item->price_cash = $request->price_cash;
            $item->public_view = $request->public_view;
            $item->collectible = $request->collectible;
            $item->save();

            if ($item->type != 8) {
                if ($item->type != 7 && $request->hasFile('image')) {
                    $extension = $request->file('image')->extension();
                    $mimeType = $request->file('image')->getMimeType();
                    $path = uploadAsset($item->type, $item->id, 'png', $request->file('image'));
                }

                if ($item->type != 2 && $request->hasFile('model')) {
                    $extension = $request->file('model')->extension();
                    $mimeType = $request->file('model')->getMimeType();
                    $path = uploadAsset($item->type, $item->id, 'obj', $request->file('model'));
                }

                if ($request->hasFile('image') || $request->hasFile('model')) {
                    render('item', $item->id);
                }
            }
        } else {
            $item->name = $request->name;
            $item->description = $request->description;
            $item->onsale = $request->onsale;
            $item->price_coins = $request->price_coins;
            $item->price_cash = $request->price_cash;
            $item->save();

            if ($request->hasFile('image') && (Auth::user()->power == 1 || Auth::user()->power > 2)) {
                $extension = $request->file('image')->extension();
                $mimeType = $request->file('image')->getMimeType();
                $path = uploadAsset($item->type, $item->id, 'png', $request->file('image'));

                render('item', $item->id);
            }
        }

        Auth::user()->updateFlood();

        return redirect()->route('market.show', ['id' => $item->id])->with('success_message', 'Item has been updated!');
    }
}
