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

use Illuminate\Http\Request;

class NotesController extends Controller
{
    public function index($page)
    {
        switch ($page) {
            case 'terms':
                $title = 'Terms of Service';
                $active = 'terms';
                $file = 'notes._terms';
                break;
            case 'privacy':
                $title = 'Privacy Policy';
                $active = 'privacy';
                $file = 'notes._privacy';
                break;
            case 'about':
                $title = 'About';
                $active = 'about';
                $file = 'notes._about';
                break;
            case 'jobs':
                $title = 'Jobs';
                $active = 'jobs';
                $file = 'notes._jobs';
                break;
            case 'team':
                $title = 'Team';
                $active = 'team';
                $file = 'notes._team';
                break;
            case 'contact':
                $title = 'Contact';
                $active = 'contact';
                $file = 'notes._contact';
                break;
            default:
                abort(404);
        }

        return view('notes.index', [
            'title' => $title,
            'active' => $active,
            'file' => $file
        ]);
    }
}
