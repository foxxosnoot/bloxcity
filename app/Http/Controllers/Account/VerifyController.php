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

use Carbon\Carbon;
use App\Mail\VerifyAccount;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EmailVerifyHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerifyController extends Controller
{
    public function index()
    {
        if (EmailVerifyHistory::where('user_id', '=', Auth::user()->id)->exists()) {
            $email = EmailVerifyHistory::where('user_id', '=', Auth::user()->id)->orderBy('created_at', 'DESC')->first();
            $emailSent = ((strtotime($email->created_at) + 600) > time());
        }

        return view('account.verify')->with([
            'emailSent' => $emailSent ?? false
        ]);
    }

    public function send()
    {
        if (!empty(Auth::user()->email_verified_at)) {
            return back()->withErrors(['You have already verified your email.']);
        }

        $code = Str::random(50);
        $now = Carbon::now()->toDateTimeString();

        if (EmailVerifyHistory::where('user_id', '=', Auth::user()->id)->exists()) {
            $email = EmailVerifyHistory::where('user_id', '=', Auth::user()->id)->first();

            if ((strtotime($email->created_at) + 600) > time()) {
                return back()->withErrors(['You have already sent an email within the last 5 minutes.']);
            }
        }

        $emailHistory = EmailVerifyHistory::create([
            'user_id' => Auth::user()->id,
            'code' => $code
        ]);

        Mail::to(Auth::user())->send(new VerifyAccount(Auth::user()));

        return back()->with('success_message', 'An email has been sent!');
    }

    public function confirm($code)
    {
        if (!empty(Auth::user()->email_verified_at)) {
            return redirect()->route('dashboard')->withErrors(['You have already verified your email.']);
        }

        $email = EmailVerifyHistory::where([['user_id', '=', Auth::user()->id], ['code', '=', $code]])->firstOrFail();
        $now = Carbon::now()->toDateTimeString();

        if ((strtotime($email->created_at) + 86400) < time()) {
            abort(404);
        }

        $myU = Auth::user();
        $myU->email_verified_at = $now;
        $myU->save();

        return redirect()->route('dashboard')->with('success_message', 'Your account has been verified!');
    }
}
