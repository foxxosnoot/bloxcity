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

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MoneyController extends Controller
{
    public function index()
    {
        return view('account.money');
    }

    public function update(Request $request)
    {
        $allowedCurrencies = ['coins', 'cash'];

        if (!in_array($request->currency, $allowedCurrencies)) {
            abort(404);
        }

        if (Auth::user()->flooding()) {
            return back()->withErrors(['You are trying to do things too fast.']);
        }

        if ($request->currency == 'coins') {
            $this->validate(request(), [
                'amount' => ['required', 'numeric', 'min:10']
            ], [
                'amount.min' => 'Amount must be divisible by 10!'
            ]);

            if ($request->amount / 10 != (int) ($request->amount / 10)) {
                return back()->withErrors(['Amount must be divisible by 10!']);
            }

            $newCoins = Auth::user()->currency_coins - $request->amount;
            $newCash = Auth::user()->currency_cash + (int) ($request->amount / 10);

            if ($newCoins >= 0 && $newCash >= 0) {
                $myU = Auth::user();
                $myU->currency_coins = $newCoins;
                $myU->currency_cash = $newCash;
                $myU->save();

                Auth::user()->updateFlood();

                return back()->with('success_message', 'Currencies have been converted successfully!');
            } else {
                return back()->withErrors(['Insufficient Coins!']);
            }
        } else if ($request->currency == 'cash') {
            $this->validate(request(), [
                'amount' => ['required', 'numeric', 'min:1']
            ]);

            $newCoins = Auth::user()->currency_coins + 10 * $request->amount;
            $newCash = Auth::user()->currency_cash - $request->amount;

            if ($newCoins >= 0 && $newCash >= 0) {
                $myU = Auth::user();
                $myU->currency_coins = $newCoins;
                $myU->currency_cash = $newCash;
                $myU->save();

                Auth::user()->updateFlood();

                return back()->with('success_message', 'Currencies have been converted successfully!');
            } else {
                return back()->withErrors(['Insufficient Cash!']);
            }
        }
    }
}
