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

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class BloxMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $currentRoute = $request->route()->getName();

        if (settings('maintenance_enabled') && !session()->has('maintenance_code') && $currentRoute != 'maintenance' && $currentRoute != 'maintenance.authenticate') {
            return redirect()->route('maintenance');
        }

        if ((settings('maintenance_enabled') && !in_array(session('maintenance_code'), config('blox.maintenance_passcodes'))) || !settings('maintenance_enabled') && session()->has('maintenance_code')) {
            session()->forget('maintenance_code');
        }

        if (Auth::check()) {
            $myU = Auth::user();

            if (strtotime($myU->next_currency_payout) < time()) {
                $myU->currency_coins += 10;
                $myU->currency_cash += $myU->dailyCash();
                $myU->next_currency_payout = Carbon::tomorrow();
                $myU->save();
            }

            if ((strtotime($myU->updated_at) + 150) < time()) {
                $myU->updated_at = Carbon::now()->toDateTimeString();
                $myU->save();
            }

            if (empty($myU->email_verified_at) && $currentRoute != 'logout' && $currentRoute != 'account.verify.index' && $currentRoute != 'account.verify.send' && $currentRoute != 'account.verify.confirm') {
                return redirect()->route('account.verify.index');
            }

            if ($myU->banned && $currentRoute != 'account.banned.index' && $currentRoute != 'account.banned.update' && $currentRoute != 'notes' && $currentRoute != 'logout') {
                return redirect()->route('account.banned.index');
            }
        }

        return $next($request);
    }
}
