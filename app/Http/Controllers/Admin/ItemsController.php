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

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ItemsController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->power < 2) {
            abort(404);
        }

        $total = Item::all()->count();

        if ($request->has('search')) {
            $item = Item::where('id', '=', $request->search)->with(['creator'])->first();
        }

        return view('admin.items')->with([
            'total' => $total,
            'item' => $item ?? null
        ]);
    }

    public function modify(Request $request)
    {
        $allowedTypes = ['render', 'accept'];

        if (!isset($request->item_id)) {
            abort(404);
        }

        if (!Item::where('id', '=', $request->item_id)->exists()) {
            abort(404);
        }

        if (!in_array($request->type, $allowedTypes) || Auth::user()->power < 2) {
            abort(404);
        }

        $item = Item::where('id', '=', $request->item_id)->first();
        $item->timestamps = false;

        if ($request->type == 'render') {
            render('item', $request->item_id);

            return back()->with('success_message', 'Item has been re-rendered!');
        } else if ($request->type == 'accept') {
            if ($item->status == 'accepted') {
                return abort(404);
            }

            render('item', $item->id);

            $item->status = 'accepted';
            $item->save();

            return back()->with('success_message', 'Item has been accepted!');
        }
    }

    public function remove(Request $request)
    {
        $allowedTypes = ['decline', 'name', 'description'];

        if (!isset($request->item_id)) {
            abort(404);
        }

        if (!Item::where('id', '=', $request->item_id)->exists()) {
            abort(404);
        }

        if (!in_array($request->type, $allowedTypes) || Auth::user()->power < 2) {
            abort(404);
        }

        $item = Item::where('id', '=', $request->item_id)->first();
        $item->timestamps = false;

        if ($request->type == 'decline') {
            if ($item->status != 'accepted') {
                return abort(404);
            }

            $item->status = 'declined';
            $item->thumbnail_url = null;
            $item->save();

            deleteAsset($item->thumbnail_url);

            return back()->with('success_message', 'Item has been declined!');
        } else if ($request->type == 'name') {
            $item->scrubName();

            return back()->with('success_message', 'Item name has been successfully scrubbed!');
        } else if ($request->type == 'description') {
            $item->scrubDescription();

            return back()->with('success_message', 'Item name has been successfully scrubbed!');
        }
    }
}
