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

use App\Models\Item;

return [
    'flood_time' => 15,
    'featured_items' => [1, 2],
    'market_categories' => [
        [
            'id' => 6,
            'title' => 'Egg Hunt 2021',
            'visible' => true
        ],
        [
            'id' => 5,
            'title' => 'Community Creations',
            'visible' => true
        ],
        [
            'id' => 1,
            'title' => 'Recently Released',
            'visible' => true
        ],
        [
            'id' => 2,
            'title' => 'Hair',
            'visible' => true
        ],
        [
            'id' => 3,
            'title' => 'Hats',
            'visible' => true
        ],
        [
            'id' => 4,
            'title' => 'Faces',
            'visible' => true
        ]
    ],
    'renderer_ip' => env('RENDERER_IP'),
    'renderer_asset_dir' => 'W2I9W2KWMNSBVCGYU892OKSN',
    'item_notifier' => [
        'enabled' => false,
        'url' => 'https://discord.com/api/webhooks/812470714401554444/n-lA8LM7rWMq2n7gu4UnEM7d-ZBQoILnpz7zLHygh2Swbko4iBeAvHhdR_MI28ds13Vp'
    ],
    'domains' => [
        'storage' => env('STORAGE_DOMAIN'),
        'blog' => 'https://blog.blox-city.com',
        'corp' => 'https://corp.blox-city.com'
    ],
    'emails' => [
        'support' => 'hello@blox-city.com',
        'moderation' => 'moderation@blox-city.com',
        'careers' => 'hello@blox-city.com',
        'payments' => 'payments@blox-city.com'
    ],
    'socials' => [
        'discord' => 'https://discord.gg/gJ5KNzWxtv',
        'twitter' => 'https://twitter.com/GameBLOXCity',
        'youtube' => '#'
    ],
    'forum_banners' => [
        'admin' => env('STORAGE_DOMAIN') . '/img/forum/bc-forummod.png',
        'VIP_1' => env('STORAGE_DOMAIN') . '/img/forum/BronzeVIP.png',
        'VIP_2' => env('STORAGE_DOMAIN') . '/img/forum/SilverVIP.png',
        'VIP_3' => env('STORAGE_DOMAIN') . '/img/forum/GoldVIP.png',
        'VIP_4' => env('STORAGE_DOMAIN') . '/img/forum/PlatinumVIP.png'
    ],
    'thumbnails' => [
        'pending' => env('STORAGE_DOMAIN') . '/web-img/market/pending.png',
        'declined' => env('STORAGE_DOMAIN') . '/web-img/market/declined.png'
    ],
    'maintenance_passcodes' => [
        'TESTKEY13022021FORPREVIEWANDOTHERSTUFF28282'
    ],
    'daily_cash' => [
        'VIP_0' => 0,
        'VIP_1' => 10,
        'VIP_2' => 30,
        'VIP_3' => 70,
        'VIP_4' => 130
    ],
    'tax_floors' => [
        'VIP_0' => 1.3,
        'VIP_1' => 1.15,
        'VIP_2' => 1.1,
        'VIP_3' => 1.05,
        'VIP_4' => 1
    ],
    'achievements' => [
        /**
         * Special
         */
        'admin' => [
            'name' => 'Administrator',
            'description' => 'Players who possess this achievement are official administrators. Administrators are members of staff who oversee the website and keep it running smoothly.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/admin-bc-new.png',
            'type' => 'special'
        ],
        'asset_helper' => [
            'name' => 'Asset Helper',
            'description' => 'Players who possess this achievement have had one or more of their items uploaded to the market. Their creation abilities are endorsed and recognized by staff.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/AssetHelper.png',
            'type' => 'special'
        ],
        'endorsed' => [
            'name' => 'Endorsed Player',
            'description' => 'Players who possess this achievement are known by administrators for their helpfulness in the community. This achievement is only obtainable if an admin grants it to you and is not available upon request.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/EndorsedUser.png',
            'type' => 'special'
        ],
        'beta' => [
            'name' => 'Beta Tester',
            'description' => 'These are not your everyday BLOX Citizens! They help us with finding bugs on our website and our games when we plan on releasing a new update! Please note that these players are not staff members.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/beta.png',
            'type' => 'special'
        ],
        /**
         * Membership
         */
        'VIP_1' => [
            'name' => 'Bronze VIP Membership',
            'description' => 'Players who possess this achievement have a Bronze VIP Membership. To view details about this special package or other account upgrades, visit our <a href="/upgrade">upgrade page</a>.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/bronzeVip.png',
            'type' => 'membership'
        ],
        'VIP_2' => [
            'name' => 'Silver VIP Membership',
            'description' => 'Players who possess this achievement have a Silver VIP Membership. To view details about this special package or other account upgrades, visit our <a href="/upgrade">upgrade page</a>.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/silverVip.png',
            'type' => 'membership'
        ],
        'VIP_3' => [
            'name' => 'Gold VIP Membership',
            'description' => 'Players who possess this achievement have a Gold VIP Membership. To view details about this special package or other account upgrades, visit our <a href="/upgrade">upgrade page</a>.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/goldVip.png',
            'type' => 'membership'
        ],
        'VIP_4' => [
            'name' => 'Platinum VIP Membership',
            'description' => 'Players who possess this achievement have a Platinum VIP Membership and are the elite of the elite. To view details about this special package or other account upgrades, visit our <a href="/upgrade">upgrade page</a>.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/platinumVip.png',
            'type' => 'membership'
        ],
        /**
         * General
         */
        'collector' => [
            'name' => 'Collector',
            'description' => 'Players who possess this achievement have obtained at least ten collectible items on their account and are generally pretty experienced within the marketplace.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/Collector.png',
            'type' => 'general'
        ],
        'rich' => [
            'name' => 'Gold Mine',
            'description' => 'Players who possess this achievement have reached at least $5,000 cash on their account. These users are often experienced in the stock market.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/GoldMine.png',
            'type' => 'general'
        ],
        'stockpiler' => [
            'name' => 'Stockpiler',
            'description' => 'Players who possess this achievement have thirty or more shop items, collectible or non-collectible, in their inventory.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/Stockpiler.png',
            'type' => 'general'
        ],
        'inviter' => [
            'name' => 'Inviter',
            'description' => 'Players who possess this achievement have referred at least five users to that have signed up with their referral code.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/Inviter.png',
            'type' => 'general'
        ],
        'forumer' => [
            'name' => 'Forumer',
            'description' => 'Players who possess this achievements have made 500 or more forum posts.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/Forumer.png',
            'type' => 'general'
        ],
        'veteran' => [
            'name' => 'Oldieblox',
            'description' => 'Players who possess this achievement have been a part of for at least one year. These players are often experienced and have semi-long beards of wiseness.',
            'image' => env('STORAGE_DOMAIN') . '/img/achievements/Oldieblox.png',
            'type' => 'general'
        ]
    ]
];
