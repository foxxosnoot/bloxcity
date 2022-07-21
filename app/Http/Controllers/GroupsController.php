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

use App\Models\Group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroupsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search ?? '';

        $groups = Group::where([
            ['name', 'LIKE', '%' . $search . '%'],
            ['disabled', '=', false]
        ])->orderBy('updated_at', 'ASC')->paginate(10);

        return view('groups.index')->with([
            'groups' => $groups
        ]);
    }

    public function show($id)
    {
        $group = Group::where('id', '=', $id)->firstOrFail();

        return view('groups.show')->with([
            'group' => $group
        ]);
    }
}
