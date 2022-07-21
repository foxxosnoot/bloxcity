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

use App\Models\SiteSettings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SiteSettingsController extends Controller
{
    public function index()
    {
        if (Auth::user()->power < 4) {
            abort(404);
        }

        return view('admin.site-settings');
    }

    public function update(Request $request)
    {
        $allowedSettings = ['maintenance', 'alert', 'upgrades', 'market_purchases', 'forum', 'creator_area', 'character', 'settings', 'registration', 'alert_message'];

        if (!in_array($request->setting, $allowedSettings) || Auth::user()->power < 4) {
            abort(404);
        }

        if ($request->setting == 'alert_message') {
            $this->validate(request(), [
                'message' => ['max:255']
            ]);

            $column = $request->setting;
            $newValue = $request->message;
        } else {
            $this->validate(request(), [
                'enabled' => ['required', 'numeric', 'min:0', 'max:1']
            ]);

            $column = $request->setting .'_enabled';
            $newValue = $request->enabled;
        }

        $word = ($request->enabled) ? 'enabled' : 'disabled';

        switch ($request->setting) {
            case 'maintenance':
                $string = 'Maintenance has been '. $word;
                break;
            case 'alert':
                $string = 'Alert has been '. $word;
                break;
            case 'upgrades':
                $string = 'Upgrades have been '. $word;
                break;
            case 'market_purchases':
                $string = 'Market purchases have been '. $word;
                break;
            case 'forum':
                $string = 'Forum has been '. $word;
                break;
            case 'creator_area':
                $string = 'Creator Area has been '. $word;
                break;
            case 'character':
                $string = 'Character Editing has been '. $word;
                break;
            case 'settings':
                $string = 'Acciunt Settings have been '. $word;
                break;
            case 'registration':
                $string = 'Registration has been '. $word;
                break;
            case 'alert_message':
                $string = 'Alert message has been updated';
                break;
        }

        $siteSettings = SiteSettings::where('id', '=', 1)->first();
        $siteSettings->{$column} = $newValue;
        $siteSettings->update();

        return back()->with('success_message', $string .' successfully!');
    }
}
