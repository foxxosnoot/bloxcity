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
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CreatorAreaController extends Controller
{
    private $allowedTypes = ['hat', 'face', 'accessory', 't-shirt', 'shirt', 'pants', 'head', 'set'];
    private $adminOnlyTypes = ['hat', 'face', 'accessory', 'head', 'set'];

    public function index()
    {
        $links[] = [
            'title' => 'T-Shirt',
            'type' => 't-shirt',
            'image' => storage('web-img/create/t-shirt.png')
        ];

        $links[] = [
            'title' => 'Shirt',
            'type' => 'shirt',
            'image' => storage('web-img/create/shirt.png')
        ];

        $links[] = [
            'title' => 'Pants',
            'type' => 'pants',
            'image' => storage('web-img/create/pants.png')
        ];

        return view('creator-area.index')->with([
            'links' => $links
        ]);
    }

    public function create($type)
    {
        if (!settings('creator_area_enabled') && ($type == 't-shirt' || $type == 'shirt' || $type == 'pants')) {
            return redirect()->route('creator-area.index');
        }

        $type = strtolower($type);
        $layout = ($type == 't-shirt' || $type == 'shirt' || $type == 'pants') ? 'master' : 'admin';

        if (!in_array($type, $this->allowedTypes)) {
            abort(404);
        }

        if ((Auth::user()->power == 0 || Auth::user()->power == 2) && in_array($type, $this->adminOnlyTypes)) {
            abort(404);
        }

        switch ($type) {
            case 'hat':
                $tips = [
                    'The texture can only be a PNG, JPG or JPEG file.'
                ];
                break;
            case 'face':
                $tips = [
                    'The image can only be a PNG, JPG or JPEG file.',
                    'The image must be 512x512 pixels.',
                    'Use the <a onclick="faceTemplate()" style="cursor:pointer;">template</a>.'
                ];
                break;
            case 'accessory':
                $tips = [
                    'The texture can only be a PNG, JPG or JPEG file.'
                ];
                break;
            case 'shirt':
                $template = 'img/templates/shirt.png';
                break;
            case 'pants':
                $template = 'img/templates/pants.png';
                break;
            case 'head':
                $tips = [
                    'Do not upload without a UV map, or the face won\'t be able to show up.'
                ];
                break;
            case 'set':
                $tips = [
                    'A set must include at least 2 items.'
                ];
                break;
        }

        return view('creator-area.create')->with([
            'type' => $type,
            'layout' => $layout,
            'template' => $template ?? null,
            'tips' => $tips ?? null
        ]);
    }

    public function store(Request $request)
    {
        if (!settings('creator_area_enabled') && ($request->type == 't-shirt' || $request->type == 'shirt' || $request->type == 'pants')) {
            return redirect()->route('creator-area.index');
        }

        if (!in_array($request->type, $this->allowedTypes)) {
            abort(404);
        }

        if ((Auth::user()->power == 0 || Auth::user()->power == 2) && in_array($request->type, $this->adminOnlyTypes)) {
            abort(404);
        }

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        $justRender = (in_array($request->type, $this->adminOnlyTypes)) ? true : false;
        $imageDimensions = ($request->type == 'face') ? 512 : 887;
        if ($request->type == 't-shirt') {
            $imageRules = ['required', 'dimensions:min_width=100,min_height=100', 'mimes:png,jpg,jpeg', 'max:2048'];
        } else {
            $imageRules = ['required', 'dimensions:min_width=' . $imageDimensions . ',min_height=' . $imageDimensions . ',min_height=' . $imageDimensions . ',max_height=' . $imageDimensions, 'mimes:png,jpg,jpeg', 'max:2048'];
        }

        if ($request->type == 'hat' || $request->type == 'accessory') {
            $this->validate(request(), [
                'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/]+$/i'],
                'description' => ['max:1024'],
                'onsale' => ['required', 'numeric', 'min:0', 'max:1'],
                'price_coins' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'price_cash' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'public_view' => ['required', 'numeric', 'min:0', 'max:1'],
                'official' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible_stock' => ['required', 'numeric', 'min:0'],
                'image' => ['required', 'mimes:png,jpg,jpeg', 'max:2048'],
                'model' => ['required', 'mimes:txt', 'max:2048']
            ]);

            $status = 'accepted';
        } else if ($request->type == 'face') {
            $this->validate(request(), [
                'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/]+$/i'],
                'description' => ['max:1024'],
                'onsale' => ['required', 'numeric', 'min:0', 'max:1'],
                'price_coins' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'price_cash' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'public_view' => ['required', 'numeric', 'min:0', 'max:1'],
                'official' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible_stock' => ['required', 'numeric', 'min:0'],
                'image' => $imageRules
            ]);

            $status = 'accepted';
        } else if ($request->type == 'head') {
            $this->validate(request(), [
                'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/]+$/i'],
                'description' => ['max:1024'],
                'onsale' => ['required', 'numeric', 'min:0', 'max:1'],
                'price_coins' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'price_cash' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'public_view' => ['required', 'numeric', 'min:0', 'max:1'],
                'official' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible_stock' => ['required', 'numeric', 'min:0'],
                'model' => ['required', 'mimes:txt', 'max:2048']
            ]);

            $status = 'accepted';
        } else if ($request->type == 'set') {
            $this->validate(request(), [
                'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/]+$/i'],
                'description' => ['max:1024'],
                'onsale' => ['required', 'numeric', 'min:0', 'max:1'],
                'price_coins' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'price_cash' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'public_view' => ['required', 'numeric', 'min:0', 'max:1'],
                'official' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible' => ['required', 'numeric', 'min:0', 'max:1'],
                'collectible_stock' => ['required', 'numeric', 'min:0'],
                'items' => ['required']
            ]);

            $status = 'accepted';
        } else {
            $this->validate(request(), [
                'name' => ['required', 'min:3', 'max:70', 'regex:/^[a-z0-9 .\-!,\':;<>?()\[\]+=\/]+$/i'],
                'description' => ['max:1024'],
                'onsale' => ['required', 'numeric', 'min:0', 'max:1'],
                'price_coins' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'price_cash' => ['required', 'numeric', 'min:0', 'max:1000000'],
                'image' => $imageRules
            ]);

            $status = 'pending';
        }

        if (isProfanity($request->name) || isProfanity($request->description)) {
            return back()->withErrors(['One or more words in your item name or description triggered our profanity filter. Please update and try again.']);
        }

        if ($request->onsale == 1 && $request->price_coins == 0 && $request->price_cash == 0) {
            return back()->withErrors(['You must sell this item for Coins or Cash.']);
        }

        if ($request->onsale == 0 && $request->collectible == 1) {
            return back()->withErrors(['A Collectible can not be off sale!']);
        }

        if ($request->collectible == 1 && $request->collectible_stock == 0) {
            return back()->withErrors(['A Collectible must have more than 1 stock.']);
        }

        $collectibleStock = ($request->type == 'hat' || $request->type == 'face' || $request->type == 'accessory' || $request->type == 'head' || $request->type == 'set') ? (($request->collectible_stock == 0) ? null : $request->collectible_stock) : null;

        switch ($request->type) {
            case 'hat': $type = 1; break;
            case 'face': $type = 2; break;
            case 'accessory': $type = 3; break;
            case 't-shirt': $type = 4; break;
            case 'shirt': $type = 5; break;
            case 'pants': $type = 6; break;
            case 'head': $type = 7; break;
            case 'set': $type = 8; break;
        }

        if ($request->type == 'hat' || $request->type == 'face' || $request->type == 'accessory' || $request->type == 'head' || $request->type == 'set') {
            $creator = ($request->official == 1) ? 1 : Auth::user()->id;
            $publicView = $request->public_view;
        } else {
            $creator = Auth::user()->id;
            $publicView = true;
        }

        $item = Item::create([
            'creator_id' => $creator,
            'name' => $request->name,
            'description' => $request->description,
            'public_view' => $publicView,
            'onsale' => $request->onsale,
            'price_coins' => $request->price_coins,
            'price_cash' => $request->price_cash,
            'type' => $type,
            'status' => $status,
            'collectible' => $request->collectible ?? false,
            'collectible_stock' => $collectibleStock,
            'collectible_stock_original' => $collectibleStock
        ]);

        Auth::user()->updateFlood();

        $inventoryItem = Inventory::create([
            'user_id' => Auth::user()->id,
            'item_id' => $item->id
        ]);

        if ($request->type != 'set') {
            if ($request->type != 'head') {
                $extension = $request->file('image')->extension();
                $mimeType = $request->file('image')->getMimeType();
                $path = uploadAsset($request->type, $item->id, 'png', $request->file('image'));
            }

            if ($request->type == 'hat' || $request->type == 'accessory' || $request->type == 'head') {
                $extension = $request->file('model')->extension();
                $mimeType = $request->file('model')->getMimeType();
                $path = uploadAsset($request->type, $item->id, 'obj', $request->file('model'));
            }
        }

        if ($justRender) render('item', $item->id);

        if ($item->public_view && ($item->type == 1 || $item->type == 2 || $item->type == 3 || $item->type == 7 || $item->type == 8)) {
            $item->notifyDiscord();
        }

        $string = ($status == 'pending') ? 'Your item has been created and is currently awaiting approval.' : 'Your item has been created and has been automatically accepted.';
        return redirect()->route('market.show', ['id' => $item->id])->with('success_message', $string);
    }
}
