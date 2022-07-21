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

use Carbon\Carbon;
use App\Models\Item;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->power < 2) {
            abort(404);
        }

        $total = User::all()->count();

        if ($request->has('search')) {
            $user = User::where('username', '=', $request->search)->first();
        }

        return view('admin.users')->with([
            'user' => $user ?? null,
            'total' => $total
        ]);
    }

    public function grant(Request $request)
    {
        $allowedTypes = ['currency', 'item', 'vip'];

        if (!isset($request->user_id)) {
            abort(404);
        }

        if (!User::where('id', '=', $request->user_id)->exists()) {
            abort(404);
        }

        if (!in_array($request->type, $allowedTypes) || Auth::user()->power < 4) {
            abort(404);
        }

        $user = User::where('id', '=', $request->user_id)->first();

        if ($request->type == 'currency') {
            $allowedCurrencies = ['coins', 'cash'];
            $currencyColumn = 'currency_'. $request->currency;

            $this->validate(request(), [
                'currency' => ['required'],
                'amount' => ['required', 'numeric', 'min:1', 'max:1000000']
            ]);

            if (!in_array($request->currency, $allowedCurrencies)) {
                return back()->withErrors(['Invalid currency.']);
            }

            $user->timestamps = false;
            $user->{$currencyColumn} += $request->amount;
            $user->save();

            return back()->with('success_message', 'This user has been granted <strong>'. $request->amount .' '. ucfirst($request->currency) .'</strong>.');
        } else if ($request->type == 'item') {
            $this->validate(request(), [
                'id' => ['required', 'numeric']
            ]);

            if (!Item::where('id', '=', $request->id)->exists()) {
                return back()->withErrors(['This item does not exist.']);
            }

            $item = Item::where('id', '=', $request->id)->first();

            if (!$item->collectible) {
                if (Inventory::where([
                    ['user_id', '=', $user->id],
                    ['item_id', '=', $item->id]
                ])->exists()) {
                    return back()->withErrors(['User already owns this item.']);
                }
            }

            $inventoryItem = Inventory::create([
                'user_id' => $user->id,
                'item_id' => $item->id
            ]);

            return back()->with('success_message', 'This user has been granted a copy of <strong>'. $item->name .'</strong>.');
        } else if ($request->type == 'vip') {
            $allowedLevels = [1, 2, 3, 4];
            $allowedTimes = ['1_month', '3_months', '6_months', '12_months', 'lifetime'];

            $this->validate(request(), [
                'plan' => ['required', 'numeric'],
                'time' => ['required']
            ]);

            if (!in_array($request->plan, $allowedLevels)) {
                return back()->withErrors(['Invalid VIP plan.']);
            }

            if (!in_array($request->time, $allowedTimes)) {
                return back()->withErrors(['Invalid time.']);
            }

            switch ($request->time) {
                case '1_month':
                    $timeSeconds = 2629800;
                    break;
                case '3_months':
                    $timeSeconds = 7889400;
                    break;
                case '6_months':
                    $timeSeconds = 15778800;
                    break;
                case '12_months':
                    $timeSeconds = 31557600;
                    break;
                case 'lifetime':
                    $timeSeconds = 31536000;
                    break;
            }

            $user->timestamps = false;
            $user->vip = $request->plan;
            $user->vip_until = Carbon::createFromTimestamp(time() + $timeSeconds)->format('Y-m-d H:i:s');
            $user->save();

            switch ($request->plan) {
                case 1:
                    $vipLevel = 'Bronze VIP';
                    break;
                case 2:
                    $vipLevel = 'Silver VIP';
                    break;
                case 3:
                    $vipLevel = 'Gold VIP';
                    break;
                case 4:
                    $vipLevel = 'Platinum VIP';
                    break;
            }

            switch ($request->time) {
                case '1_month':
                    $vipTime = '1 Month';
                    break;
                case '3_months':
                    $vipTime = '3 Months';
                    break;
                case '6_months':
                    $vipTime = '6 Months';
                    break;
                case '12_months':
                    $vipTime = '12 Months';
                    break;
                case 'lifetime':
                    $vipTime = 'a Lifetime';
                    break;
            }

            return back()->with('success_message', 'This user has been granted <strong>'. $vipTime .' of '. $vipLevel .'</strong>.');
        }
    }

    public function modify(Request $request)
    {
        $allowedTypes = ['currency', 'vip', 'username', 'render'];

        if (!isset($request->user_id)) {
            abort(404);
        }

        if (!User::where('id', '=', $request->user_id)->exists()) {
            abort(404);
        }

        if (!in_array($request->type, $allowedTypes) || Auth::user()->power < 4) {
            abort(404);
        }

        $user = User::where('id', '=', $request->user_id)->first();
        $user->timestamps = false;

        if ($request->type == 'currency') {
            $allowedCurrencies = ['coins', 'cash'];
            $currencyColumn = 'currency_'. $request->currency;

            $this->validate(request(), [
                'currency' => ['required'],
                'amount' => ['required', 'numeric', 'min:0', 'max:1000000']
            ]);

            if (!in_array($request->currency, $allowedCurrencies)) {
                return back()->withErrors(['Invalid currency.']);
            }

            $user->{$currencyColumn} = $request->amount;
            $user->save();

            return back()->with('success_message', ucfirst($request->currency) .' has been successfully modified.');
        } else if ($request->type == 'vip') {
            $allowedLevels = [0, 1, 2, 3, 4];

            $this->validate(request(), [
                'plan' => ['required', 'numeric']
            ]);

            if (!in_array($request->plan, $allowedLevels)) {
                return back()->withErrors(['Invalid VIP plan.']);
            }

            $user->vip = $request->plan;

            if ($request->plan == 0) {
                $user->vip_until = null;
            }

            $user->save();

            return back()->with('success_message', 'VIP has been successfully modified!');
        } else if ($request->type == 'username') {
            $this->validate(request(), [
                'username' => ['required', 'min:3', 'max:20', 'regex:/\\A[a-z\\d]+(?:[.-][a-z\\d]+)*\\z/i', 'unique:users']
            ]);

            $user->username = $request->username;
            $user->save();

            return redirect()->route('admin.users', ['search' => $user->username])->with('success_message', 'Username has been successfully modified!');
        } else if ($request->type == 'render') {
            render('user', $user->id);

            return back()->with('success_message', 'User has been re-rendered!');
        }
    }

    public function remove(Request $request)
    {
        $allowedTypes = ['avatar', 'username', 'description', 'signature'];

        if (!isset($request->user_id)) {
            abort(404);
        }

        if (!User::where('id', '=', $request->user_id)->exists()) {
            abort(404);
        }

        if (!in_array($request->type, $allowedTypes) || Auth::user()->power < 2) {
            abort(404);
        }

        $user = User::where('id', '=', $request->user_id)->first();

        if ($request->type == 'avatar') {
            $user->resetAvatar();

            return back()->with('success_message', 'Avatar has been successfully reset!');
        } else if ($request->type == 'username') {
            $user->scrubUsername();

            return redirect()->route('admin.users', ['search' => $user->username])->with('success_message', 'Username has been successfully scrubbed!');
        } else if ($request->type == 'description') {
            $user->scrubDescription();

            return back()->with('success_message', 'Description has been successfully scrubbed!');
        } else if ($request->type == 'signature') {
            $user->scrubSignature();

            return back()->with('success_message', 'Signature has been successfully scrubbed!');
        }
    }

}
