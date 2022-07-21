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

namespace App\Http\Controllers\Account;

use App\Models\Item;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    public function index()
    {
        return view('account.character');
    }

    public function src()
    {
        if (!settings('character_enabled')) {
            return response()->json(['success' => false, 'message' => 'Character Customization is currently disabled.']);
        }

        return response()->json([
            'avatar' => storage(Auth::user()->avatar_url),
            'headshot' => storage(Auth::user()->headshot_url)
        ]);
    }

    public function update(Request $request)
    {
        if (!settings('character_enabled')) {
            return response()->json(['success' => false, 'message' => 'Character Customization is currently disabled.']);
        }

        $allowedActions = ['wear', 'unwear', 'color', 'angle'];

        if (!in_array($request->action, $allowedActions)) {
            return response()->json(['success' => false, 'message' => 'Invalid action.']);
        }

        $myU = Auth::user();

        if ($request->action == 'wear') {
            if (!isset($request->item_id)) {
                return response()->json(['success' => false, 'message' => 'No item ID provided.']);
            }

            if (!Item::where('id', '=', $request->item_id)->exists()) {
                return response()->json(['success' => false, 'message' => 'Item does not exist.']);
            }

            if (!Inventory::where([['item_id', '=', $request->item_id], ['user_id', '=', $myU->id]])->exists()) {
                return response()->json(['success' => false, 'message' => 'You do not own this item.']);
            }

            $item = Item::where('id', '=', $request->item_id)->first();

            if ($item->status != 'accepted') {
                return response()->json(['success' => false, 'message' => 'This item is not approved.']);
            }

            switch ($item->type) {
                case 1:
                    if (empty($myU->hat1)) {
                        $column = 'hat1';
                    } else if (empty($myU->hat2)) {
                        $column = 'hat2';
                    } else if (empty($myU->hat3)) {
                        $column = 'hat3';
                    } else {
                        $column = 'hat1';
                    }
                    break;
                case 2:
                    $column = 'face';
                    break;
                case 3:
                    $column = 'accessory';
                    break;
                case 4:
                    $column = 'tshirt';
                    break;
                case 5:
                    $column = 'shirt';
                    break;
                case 6:
                    $column = 'pants';
                    break;
                case 7:
                    $column = 'head';
                    break;
            }

            if ($column == 'hat1' || $column == 'hat2' || $column == 'hat3') {
                if ($myU->hat1 == $request->item_id || $myU->hat2 == $request->item_id || $myU->hat3 == $request->item_id) {
                    return response()->json(['success' => true]);
                }
            }

            if ($myU->{$column} == $request->item_id) {
                return response()->json(['success' => true]);
            }

            $myU->{$column} = $request->item_id;
            $myU->save();

            render('user', $myU->id);

            return response()->json(['success' => true]);
        } else if ($request->action == 'unwear') {
            $allowedTypes = ['hat1', 'hat2', 'hat3', 'face', 'accessory', 'tshirt', 'shirt', 'pants', 'head'];

            if (!in_array($request->type, $allowedTypes)) {
                return response()->json(['success' => false, 'message' => 'Invalid type.']);
            }

            if (!isset($request->type)) {
                return response()->json(['success' => false, 'message' => 'No type provided.']);
            }

            $myU->{$request->type} = null;
            $myU->save();

            render('user', $myU->id);

            return response()->json(['success' => true]);
        } else if ($request->action == 'color') {
            $allowedParts = ['head', 'torso', 'left_arm', 'right_arm', 'left_leg', 'right_leg'];

            $allowedColors = [
                'brown'                         => '141/255,85/255,36/255',
                'light-brown'                   => '198/255,134/255,66/255',
                'lighter-brown'                 => '224/255,172/255,105/255',
                'lighter-lighter-brown'         => '241/255,194/255,125/255',
                'lighter-lighter-lighter-brown' => '252/255,225/255,213/255',

                'salmon'                        => '241/255,157/255,154/255',
                'blue'                          => '118/255,159/255,202/255',
                'light-blue'                    => '162/255,209/255,230/255',
                'purple'                        => '160/255,139/255,208/255',
                'dark-purple'                   => '49/255,43/255,76/255',

                'dark-green'                    => '4/255,99/255,6/255',
                'green'                         => '27/255,132/255,44/255',
                'yellow'                        => '247/255,177/255,85/255',
                'orange'                        => '247/255,144/255,57/255',
                'red'                           => '255/255,0/255,0/255',

                'light-pink'                    => '248/255,163/255,213/255',
                'pink'                          => '255/255,14/255,154/255',
                'white'                         => '255/255,255/255,255/255',
                'gray'                          => '125/255,125/255,125/255',
                'black'                         => '0/255,0/255,0/255'
            ];

            if (!in_array($request->part, $allowedParts)) {
                return response()->json(['success' => false, 'message' => 'Invalid part.']);
            }

            if (!array_key_exists($request->color, $allowedColors)) {
                return response()->json(['success' => false, 'message' => 'Invalid color.']);
            }

            if ($myU->{'rgb_' . $request->part} == $allowedColors[$request->color]) {
                return response()->json(['success' => true]);
            }

            $myU->{'rgb_' . $request->part} = $allowedColors[$request->color];
            $myU->save();

            render('user', $myU->id);

            return response()->json(['success' => true]);
        } else if ($request->action == 'angle') {
            $allowedAngles = ['left', 'right'];

            if (!in_array($request->angle, $allowedAngles)) {
                return response()->json(['success' => false, 'message' => 'Invalid angle.']);
            }

            if ($myU->angle == $request->angle) {
                return response()->json(['success' => true]);
            }

            $myU->angle = $request->angle;
            $myU->save();

            render('user', $myU->id);

            return response()->json(['success' => true]);
        }
    }

    public function inventory(Request $request)
    {
        if (!settings('character_enabled')) {
            return response()->json(['success' => false, 'message' => 'Character Customization is currently disabled.']);
        }

        $myU = Auth::user();

        $allowedCategories = [1 => 'hats', 2 => 'faces', 3 => 'accessories', 4 => 't-shirts', 5 => 'shirts', 6 => 'pants', 7 => 'heads'];

        if (!in_array($request->category, $allowedCategories)) {
            return response()->json(['success' => false, 'message' => 'Invalid category.']);
        }

        $type = array_search($request->category, $allowedCategories);
        $items = Item::where([['type', '=', $type], ['status', '=', 'accepted']])
            ->join('inventories', 'inventories.item_id', '=', 'items.id')
            ->where('inventories.user_id', '=', Auth::user()->id)
            ->orderBy('inventories.created_at', 'DESC')
            ->paginate(8);

        if ($items->count() == 0) {
            return response()->json(['success' => false, 'message' => 'No ' . $allowedCategories[$type] . ' found.']);
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
                'thumbnail_url' => $item->thumbnail()
            ];
        }

        return response()->json($json);
    }

    public function wearing()
    {
        if (!settings('character_enabled')) {
            return response()->json(['success' => false, 'message' => 'Character Customization is currently disabled.']);
        }

        $myU = Auth::user();

        if (empty($myU->hat1) && empty($myU->hat2) && empty($myU->hat3) && empty($myU->face) && empty($myU->accessory) && empty($myU->tshirt) && empty($myU->shirt) && empty($myU->pants) && empty($myU->head)) {
            return response()->json(['success' => false, 'message' => 'You are not wearing any items.']);
        }

        $json = [];

        if (!empty($myU->hat1)) {
            $item = Item::where('id', '=', $myU->hat1)->first();
            $json[] = ['id' => $myU->hat1, 'name' => $item->name, 'thumbnail_url' => $item->thumbnail(), 'type' => 'hat1'];
        }

        if (!empty($myU->hat2)) {
            $item = Item::where('id', '=', $myU->hat2)->first();
            $json[] = ['id' => $myU->hat2, 'name' => $item->name, 'thumbnail_url' => $item->thumbnail(), 'type' => 'hat2'];
        }

        if (!empty($myU->hat3)) {
            $item = Item::where('id', '=', $myU->hat3)->first();
            $json[] = ['id' => $myU->hat3, 'name' => $item->name, 'thumbnail_url' => $item->thumbnail(), 'type' => 'hat3'];
        }

        if (!empty($myU->face)) {
            $item = Item::where('id', '=', $myU->face)->first();
            $json[] = ['id' => $myU->face, 'name' => $item->name, 'thumbnail_url' => $item->thumbnail(), 'type' => 'face'];
        }

        if (!empty($myU->accessory)) {
            $item = Item::where('id', '=', $myU->accessory)->first();
            $json[] = ['id' => $myU->accessory, 'name' => $item->name, 'thumbnail_url' => $item->thumbnail(), 'type' => 'accessory'];
        }

        if (!empty($myU->tshirt)) {
            $item = Item::where('id', '=', $myU->tshirt)->first();
            $json[] = ['id' => $myU->tshirt, 'name' => $item->name, 'thumbnail_url' => $item->thumbnail(), 'type' => 'tshirt'];
        }

        if (!empty($myU->shirt)) {
            $item = Item::where('id', '=', $myU->shirt)->first();
            $json[] = ['id' => $myU->shirt, 'name' => $item->name, 'thumbnail_url' => $item->thumbnail(), 'type' => 'shirt'];
        }

        if (!empty($myU->pants)) {
            $item = Item::where('id', '=', $myU->pants)->first();
            $json[] = ['id' => $myU->pants, 'name' => $item->name, 'thumbnail_url' => $item->thumbnail(), 'type' => 'pants'];
        }

        if (!empty($myU->head)) {
            $item = Item::where('id', '=', $myU->head)->first();
            $json[] = ['id' => $myU->head, 'name' => $item->name, 'thumbnail_url' => $item->thumbnail(), 'type' => 'head'];
        }

        return response()->json($json);
    }
}
