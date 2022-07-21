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

namespace App\Http\Controllers\API\V1;

use App\Models\Item;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Models\ItemFavorites;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function info(Request $request)
    {
        if (!$request->has('username')) {
            return response()->json(['success' => false, 'message' => 'Please provide a username.']);
        }

        if (!User::where('username', '=', $request->username)->exists()) {
            return response()->json(['success' => false, 'message' => 'User does not exist.']);
        }

        $user = User::where('username', '=', $request->username)->first();

        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'description' => $user->description,
            'status' => $user->status(),
            'admin' => $user->power > 0,
            'vip' => $user->vip > 0,
            'online' => $user->online(),
            'avatar_url' => storage($user->avatar_url),
            'headshot_url' => storage($user->headshot_url),
            'date_joined' => $user->created_at,
            'last_seen' => $user->updated_at
        ]);
    }

    public function inventory(Request $request)
    {
        if (!$request->has('id') || !$request->has('category') || !$request->has('page')) {
            return response()->json(['success' => false, 'message' => 'Please provide an id, category and page.']);
        }

        if (!User::where('id', '=', $request->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'User does not exist.']);
        }

        $allowedCategories = [1 => 'hats', 2 => 'faces', 3 => 'accessories', 4 => 't-shirts', 5 => 'shirts', 6 => 'pants', 7 => 'heads', 8 => 'collectibles'];

        if (!in_array($request->category, $allowedCategories)) {
            return response()->json(['success' => false, 'message' => 'Invalid category.']);
        }

        $user = User::where('id', '=', $request->id)->first();
        $type = array_search($request->category, $allowedCategories);

        if ($user->banned) {
            return response()->json(['success' => false, 'message' => 'User is banned.']);
        }

        if ($user->setting_inventory == 'no_one') {
            return response()->json(['success' => false, 'message' => $user->username . ' has made their inventory private.']);
        }

        if ($type == 8) {
            $items = Item::where([['collectible', '=', true], ['public_view', '=', true]])
                ->join('inventories', 'inventories.item_id', '=', 'items.id')
                ->where('inventories.user_id', '=', $user->id)
                ->orderBy('inventories.created_at', 'DESC')
                ->paginate(8);
        } else {
            $items = Item::where([['type', '=', $type], ['public_view', '=', true]])
                ->join('inventories', 'inventories.item_id', '=', 'items.id')
                ->where('inventories.user_id', '=', $user->id)
                ->orderBy('inventories.created_at', 'DESC')
                ->paginate(8);
        }

        if ($items->count() == 0) {
            return response()->json(['success' => false, 'message' => 'No '. $request->category .' found.']);
        }

        $json = [
            'current_page' => $items->currentPage(),
            'total_pages' => $items->lastPage(),
            'items' => []
        ];

        foreach ($items as $item) {
            $json['items'][] = [
                'id' => $item->item_id,
                'name' => $item->name,
                'price_coins' => $item->price_coins,
                'price_cash' => $item->price_cash,
                'onsale' => $item->onsale,
                'timed_item' => $item->timed_item,
                'offsale_at' => $item->offsale_at,
                'collectible' => $item->collectible,
                'thumbnail_url' => $item->thumbnail(),
                'creator' => [
                    'id' => $item->creator->id,
                    'username' => $item->creator->username
                ]
            ];
        }

        return response()->json($json);
    }

    public function favorites(Request $request)
    {
        if (!$request->has('id') || !$request->has('page')) {
            return response()->json(['success' => false, 'message' => 'Please provide an id and page.']);
        }

        if (!User::where('id', '=', $request->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'User does not exist.']);
        }

        $user = User::where('id', '=', $request->id)->first();

        if ($user->banned) {
            return response()->json(['success' => false, 'message' => 'User is banned.']);
        }

        $favorites = ItemFavorites::where('user_id', '=', $request->id)->orderBy('created_at', 'DESC')->get();
        $favoriteItems = [];

        foreach ($favorites as $favorite) {
            $favoriteItems[] = $favorite->item_id;
        }

        $items = Item::whereIn('id', $favoriteItems)->where('public_view', '=', true)->with(['creator'])->orderBy('updated_at', 'DESC')->paginate(4);

        if ($items->count() == 0) {
            return response()->json(['success' => false, 'message' => 'No items found.']);
        }

        $json = [
            'current_page' => $items->currentPage(),
            'total_pages' => $items->lastPage(),
            'items' => []
        ];

        foreach ($items as $item) {
            $json['items'][] = [
                'id' => $item->id,
                'name' => $item->name,
                'price_coins' => $item->price_coins,
                'price_cash' => $item->price_cash,
                'onsale' => $item->onsale,
                'timed_item' => $item->timed_item,
                'offsale_at' => $item->offsale_at,
                'collectible' => $item->collectible,
                'thumbnail_url' => $item->thumbnail(),
                'creator' => [
                    'id' => $item->creator->id,
                    'username' => $item->creator->username
                ]
            ];
        }

        return response()->json($json);
    }
}
