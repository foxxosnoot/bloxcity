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
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MarketController extends Controller
{
    public function main(Request $request)
    {
        if (!$request->has('category') || !$request->has('page')) {
            return response()->json(['success' => false, 'message' => 'Please provide a category and page.']);
        }

        $allowedCategories = [1 => 'hats', 2 => 'faces', 3 => 'accessories', 4 => 't-shirts', 5 => 'shirts', 6 => 'pants', 7 => 'heads', 8 => 'sets', 9 => 'recent'];

        if (!in_array($request->category, $allowedCategories)) {
            return response()->json(['success' => false, 'message' => 'Invalid category.']);
        }

        $type = array_search($request->category, $allowedCategories);
        $search = $request->search ?? '';

        if ($type == 9) {
            $items = Item::where([
                ['creator_id', '=', 1],
                ['public_view', '=', true],
                ['name', 'LIKE', '%' . $search . '%'],
                ['status', '=', 'accepted']
            ])->with(['creator'])->orderBy('updated_at', 'DESC')->paginate(18);
        } else {
            $items = Item::where([
                ['type', '=', $type],
                ['public_view', '=', true],
                ['name', 'LIKE', '%' . $search . '%'],
                ['status', '=', 'accepted']
            ])->with(['creator'])->orderBy('updated_at', 'DESC')->paginate(18);
        }

        if ($items->count() == 0) {
            return response()->json(['success' => false, 'message' => 'No items found.']);
        }

        foreach ($items as $item) {
            $json[] = [
                'id' => $item->id,
                'name' => $item->name,
                'price_coins' => $item->price_coins,
                'price_cash' => $item->price_cash,
                'onsale' => $item->onsale,
                'timed_item' => $item->timed_item,
                'offsale_at' => $item->offsale_at,
                'collectible' => $item->collectible,
                'collectible_stock' => $item->collectible_stock,
                'collectible_stock_original' => $item->collectible_stock_original,
                'thumbnail_url' => storage($item->thumbnail_url),
                'creator' => [
                    'id' => $item->creator->id,
                    'username' => $item->creator->username
                ]
            ];
        }

        return response()->json([
            'has_next_page' => $items->hasMorePages(),
            'current_page' => $items->currentPage(),
            'total_pages' => $items->lastPage(),
            'data' => $json
        ]);
    }

    public function purchase(Request $request)
    {
        $item = Item::where([
            ['id', '=', $request->id]
        ])->with(['creator'])->firstOrFail();

        $validator = Validator::make($request->all(), [
            'currency' => ['required', Rule::in(['coins', 'cash'])]
        ], [
            'currency.required' => 'You must provide a currency to pay with.'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'messages' => $validator->messages()]);
        }

        if ($item->status != 'accepted') {
            return response()->json(['success' => false, 'message' => 'This item is not accepted!']);
        }

        if (!$item->onsale) {
            return response()->json(['success' => false, 'message' => 'This item is not on sale!']);
        }

        if ($item->collectible && $item->collectible_stock <= 0) {
            return response()->json(['success' => false, 'message' => 'This item is out of stock!']);
        }

        if (Item::checkOwnership($item->id, Auth::user()->id)) {
            return response()->json(['success' => false, 'message' => 'You already own this item!']);
        }

        if ($item->collectible_stock_original == $item->collectible_stock) {
            $serial = 1;
        } else {
            if ($item->collectible_stock == 1) {
                $serial = $item->collectible_stock_original;
            } else {
                $serial = $item->collectible_stock_original - ($item->collectible_stock - 1);
            }
        }

        if ($request->currency == 'coins') {
            if (Auth::user()->currency_coins < $item->price_coins) {
                return back()->withErrors(['You do not have enough Coins to purchase this item!']);
            }

            if ($item->price_coins <= 0) {
                return back()->withErrors(['This item is not available for purchase with Coins!']);
            }

            $myU = Auth::user();
            $myU->currency_coins -= $item->price_coins;
            $myU->save();

            if ($item->collectible) {
                $item->collectible_stock -= 1;
                $item->save();
            }

            Item::grant($item->id, Auth::user()->id, $item->collectible, $serial);

            return response()->json(['success' => true, 'message' => 'Item has been purchased.']);
        } else if ($request->currency == 'cash') {
            if (Auth::user()->currency_cash < $item->price_cash) {
                return back()->withErrors(['You do not have enough Cash to purchase this item!']);
            }

            if ($item->price_cash <= 0) {
                return back()->withErrors(['This item is not available for purchase with Cash!']);
            }

            $myU = Auth::user();
            $myU->currency_cash -= $item->price_cash;
            $myU->save();

            if ($item->collectible) {
                $item->collectible_stock -= 1;
                $item->save();
            }

            Item::grant($item->id, Auth::user()->id, $item->collectible, $serial);

            return response()->json(['success' => true, 'message' => 'Item has been purchased.']);
        }
    }
}
