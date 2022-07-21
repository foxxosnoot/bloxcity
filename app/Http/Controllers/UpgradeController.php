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

class UpgradeController extends Controller
{
    public function index()
    {
        return view('upgrade.index');
    }

    public function show($plan)
    {
        if (!settings('upgrades_enabled')) {
            abort(404);
        }

        $allowedPlans = ['bronze-vip', 'silver-vip', 'gold-vip', 'platinum-vip', 'cash'];

        if (!in_array($plan, $allowedPlans)) {
            abort(404);
        }

        switch ($plan) {
            case 'bronze-vip':
                $title = 'Bronze VIP';
                $plans = [
                    [
                        'name' => '1 Month',
                        'paypal_name' => 'Bronze VIP (1 Month)',
                        'item_number' => 1,
                        'price' => 3.49
                    ],
                    [
                        'name' => '3 Months',
                        'paypal_name' => 'Bronze VIP (3 Months)',
                        'item_number' => 2,
                        'price' => 9.49
                    ],
                    [
                        'name' => '6 Months',
                        'paypal_name' => 'Bronze VIP (6 Months)',
                        'item_number' => 3,
                        'price' => 19.49
                    ],
                    [
                        'name' => '12 Months',
                        'paypal_name' => 'Bronze VIP (12 Months)',
                        'item_number' => 4,
                        'price' => 39.49
                    ]
                ];
                break;
            case 'silver-vip':
                $title = 'Silver VIP';
                $plans = [
                    [
                        'name' => '1 Month',
                        'paypal_name' => 'Silver VIP (1 Month)',
                        'item_number' => 5,
                        'price' => 6.49
                    ],
                    [
                        'name' => '3 Months',
                        'paypal_name' => 'Silver VIP (3 Months)',
                        'item_number' => 6,
                        'price' => 19.49
                    ],
                    [
                        'name' => '6 Months',
                        'paypal_name' => 'Silver VIP (6 Months)',
                        'item_number' => 7,
                        'price' => 39.49
                    ],
                    [
                        'name' => '12 Months',
                        'paypal_name' => 'Silver VIP (12 Months)',
                        'item_number' => 8,
                        'price' => 79.49
                    ]
                ];
                break;
            case 'gold-vip':
                $title = 'Gold VIP';
                $plans = [
                    [
                        'name' => '1 Month',
                        'paypal_name' => 'Gold VIP (1 Month)',
                        'item_number' => 9,
                        'price' => 11.49
                    ],
                    [
                        'name' => '3 Months',
                        'paypal_name' => 'Gold VIP (3 Months)',
                        'item_number' => 10,
                        'price' => 34.49
                    ],
                    [
                        'name' => '6 Months',
                        'paypal_name' => 'Gold VIP (6 Months)',
                        'item_number' => 11,
                        'price' => 69.49
                    ],
                    [
                        'name' => '12 Months',
                        'paypal_name' => 'Gold VIP (12 Months)',
                        'item_number' => 12,
                        'price' => 139.49
                    ]
                ];
                break;
            case 'platinum-vip':
                $title = 'Platinum VIP';
                $plans = [
                    [
                        'name' => '1 Month',
                        'paypal_name' => 'Platinum VIP (1 Month)',
                        'item_number' => 13,
                        'price' => 18.95
                    ],
                    [
                        'name' => '3 Months',
                        'paypal_name' => 'Platinum VIP (3 Months)',
                        'item_number' => 14,
                        'price' => 59.95
                    ],
                    [
                        'name' => '6 Months',
                        'paypal_name' => 'Platinum VIP (6 Months)',
                        'item_number' => 15,
                        'price' => 109.95
                    ],
                    [
                        'name' => '12 Months',
                        'paypal_name' => 'Platinum VIP (12 Months)',
                        'item_number' => 16,
                        'price' => 229.95
                    ]
                ];
                break;
            case 'cash':
                $title = 'Cash';
                $plans = [
                    [
                        'name' => '100 Cash',
                        'paypal_name' => '100 Cash',
                        'item_number' => 17,
                        'price' => 0.95
                    ],
                    [
                        'name' => '250 Cash',
                        'paypal_name' => '250 Cash',
                        'item_number' => 18,
                        'price' => 2.95
                    ],
                    [
                        'name' => '500 Cash',
                        'paypal_name' => '500 Cash',
                        'item_number' => 18,
                        'price' => 4.95
                    ],
                    [
                        'name' => '750 Cash',
                        'paypal_name' => '500 Cash',
                        'item_number' => 18,
                        'price' => 6.95
                    ],
                    [
                        'name' => '1000 Cash',
                        'paypal_name' => '1000 Cash',
                        'item_number' => 18,
                        'price' => 9.95
                    ],
                    [
                        'name' => '2500 Cash',
                        'paypal_name' => '2500 Cash',
                        'item_number' => 18,
                        'price' => 23.95
                    ],
                    [
                        'name' => '5000 Cash',
                        'paypal_name' => '5000 Cash',
                        'item_number' => 18,
                        'price' => 46.95
                    ],
                    [
                        'name' => '10000 Cash',
                        'paypal_name' => '10000 Cash',
                        'item_number' => 18,
                        'price' => 89.95
                    ]
                ];
                break;
        }

        return view('upgrade.show')->with([
            'plan' => $plan,
            'title' => $title,
            'plans' => $plans
        ]);
    }

    public function paypal(Request $request)
    {
        if (!settings('upgrades_enabled')) {
            abort(404);
        }

        echo '<h1>soonTM.</h1>';
    }
}
