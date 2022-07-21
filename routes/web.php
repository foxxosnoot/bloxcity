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

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers'], function() {
    Route::get('/', 'HomeController@index')->name('landing')->middleware('guest');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard')->middleware('auth');
    Route::post('/status', 'DashboardController@status')->name('status')->middleware('auth');
    Route::get('/site/offline', 'MaintenanceController@index')->name('maintenance');
    Route::get('/maintenance-exit', 'MaintenanceController@exit')->name('maintenance.exit');
    Route::post('/maintenance-access', 'MaintenanceController@authenticate')->name('maintenance.authenticate');

    /**
     * Beta
     */
    Route::group(['prefix' => 'beta'], function() {
        Route::get('/', 'BetaController@index')->name('beta.index')->middleware('auth');
        Route::post('/join', 'BetaController@update')->name('beta.update')->middleware('auth');
    });

    /**
     * Admin
     */
    Route::view('/admin', 'admin.troll');
    Route::group(['prefix' => 'adminer', 'middleware' => 'staff'], function() {
        Route::get('/', 'Admin\HomeController@index')->name('admin.dashboard');

        // Moderation
        Route::get('/asset-approval', 'Admin\AssetApprovalController@index')->name('admin.asset_approval');
        Route::post('/asset-approval/update', 'Admin\AssetApprovalController@update')->name('admin.asset_approval.update');
        Route::get('/reports', 'Admin\ReportsController@index')->name('admin.reports');
        Route::post('/reports/update', 'Admin\ReportsController@update')->name('admin.reports.update');
        Route::get('/ban/{username}', 'Admin\BanController@index')->name('admin.ban');
        Route::post('/ban/submit', 'Admin\BanController@store')->name('admin.ban.store');

        // Search
        Route::get('/items', 'Admin\ItemsController@index')->name('admin.items');
        Route::post('/items/modify', 'Admin\ItemsController@modify')->name('admin.items.modify');
        Route::post('/items/remove', 'Admin\ItemsController@remove')->name('admin.items.remove');
        Route::get('/users', 'Admin\UsersController@index')->name('admin.users');
        Route::post('/users/grant', 'Admin\UsersController@grant')->name('admin.users.grant');
        Route::post('/users/modify', 'Admin\UsersController@modify')->name('admin.users.modify');
        Route::post('/users/remove', 'Admin\UsersController@remove')->name('admin.users.remove');
        Route::view('/groups', 'admin.groups')->name('admin.groups');

        // Management
        Route::get('/site-settings', 'Admin\SiteSettingsController@index')->name('admin.site_settings');
        Route::post('/site-settings/update', 'Admin\SiteSettingsController@update')->name('admin.site_settings.update');
        Route::get('/manage-staff', 'Admin\ManageStaffController@index')->name('admin.manage_staff');
        Route::post('/manage-staff/update', 'Admin\ManageStaffController@update')->name('admin.manage_staff.update');
        Route::get('/staff', 'Admin\StaffController@index')->name('admin.staff');
    });

    /**
     * Account
     */
    Route::group(['prefix' => 'verify'], function() {
        Route::get('/', 'Account\VerifyController@index')->name('account.verify.index')->middleware('auth');
        Route::get('/confirm/{code}', 'Account\VerifyController@confirm')->name('account.verify.confirm')->middleware('auth');
        Route::post('/send', 'Account\VerifyController@send')->name('account.verify.send')->middleware('auth');
    });
    Route::group(['prefix' => 'banned'], function() {
        Route::get('/', 'Account\BannedController@index')->name('account.banned.index')->middleware('auth');
        Route::post('/activate', 'Account\BannedController@update')->name('account.banned.update')->middleware('auth');
    });
    Route::group(['prefix' => 'discord'], function() {
        Route::get('/', 'Account\DiscordController@index')->name('account.discord.index')->middleware('auth');
        Route::post('/generate', 'Account\DiscordController@update')->name('account.discord.update')->middleware('auth');
    });
    Route::group(['prefix' => 'friends'], function() {
        Route::get('/', 'Account\FriendsController@index')->name('account.friends.index')->middleware('auth');
        Route::post('/update', 'Account\FriendsController@update')->name('account.friends.update')->middleware('auth');
    });
    Route::group(['prefix' => 'inbox'], function() {
        Route::get('/', 'Account\InboxController@index')->name('account.inbox.index')->middleware('auth');
        Route::get('/view/{id}', 'Account\InboxController@show')->name('account.inbox.show')->middleware('auth');
        Route::get('/compose/{username}', 'Account\InboxController@compose')->name('account.inbox.compose')->middleware('auth');
        Route::post('/compose', 'Account\InboxController@composeStore')->name('account.inbox.compose.store')->middleware('auth');
        Route::get('/reply/{id}', 'Account\InboxController@reply')->name('account.inbox.reply')->middleware('auth');
        Route::post('/reply', 'Account\InboxController@replyStore')->name('account.inbox.reply.store')->middleware('auth');
    });
    Route::group(['prefix' => 'character'], function() {
        Route::get('/', 'Account\CharacterController@index')->name('account.character.index')->middleware('auth');
        Route::post('/update', 'Account\CharacterController@update')->name('account.character.update')->middleware('auth');
        Route::get('/inventory', 'Account\CharacterController@inventory')->name('account.character.inventory')->middleware('auth');
        Route::get('/wearing', 'Account\CharacterController@wearing')->name('account.character.wearing')->middleware('auth');
        Route::get('/src', 'Account\CharacterController@src')->name('account.character.src')->middleware('auth');
    });
    Route::group(['prefix' => 'settings'], function() {
        Route::get('/', 'Account\SettingsController@index')->name('account.settings.index')->middleware('auth');
        Route::post('/update', 'Account\SettingsController@update')->name('account.settings.update')->middleware('auth');
    });
    Route::group(['prefix' => 'money'], function() {
        Route::get('/', 'Account\MoneyController@index')->name('account.money.index')->middleware('auth');
        Route::post('/convert', 'Account\MoneyController@update')->name('account.money.update')->middleware('auth');
    });

    /**
     * Achievements
     */
    Route::get('/achievements', 'AchievementsController@index');

    /**
     * Auth
     */
    Route::get('/logout', 'Auth\LogoutController@logout')->name('logout')->middleware('auth');
    Route::get('/login', 'Auth\LoginController@index')->name('login')->middleware('guest');
    Route::post('/login', 'Auth\LoginController@authenticate')->name('login.authenticate')->middleware('guest');
    Route::get('/register', 'Auth\RegisterController@index')->name('register')->middleware('guest');
    Route::post('/register', 'Auth\RegisterController@authenticate')->name('register.authenticate')->middleware('guest');

    /**
     * Creator Area
     */
    Route::group(['prefix' => 'creator-area'], function() {
        Route::get('/', 'CreatorAreaController@index')->name('creator-area.index')->middleware('auth');
        Route::get('/create/{type}', 'CreatorAreaController@create')->name('creator-area.create')->middleware('auth');
        Route::post('/create/upload', 'CreatorAreaController@store')->name('creator-area.store')->middleware('auth');
    });

    /**
     * Forum
     */
    Route::group(['prefix' => 'forum'], function() {
        Route::get('/', 'ForumController@index')->name('forum.index');
        Route::get('/my-threads', 'ForumController@myThreads')->name('forum.my-threads')->middleware('auth');
        Route::get('/search', 'ForumController@search')->name('forum.search');
        Route::get('/topic/{id}', 'ForumController@topic')->name('forum.topic');
        Route::get('/thread/{id}', 'ForumController@thread')->name('forum.thread');
        Route::get('/reply/{id}', 'ForumController@reply')->name('forum.reply')->middleware('auth');
        Route::post('/reply/post', 'ForumController@replyStore')->name('forum.reply.store')->middleware('auth');
        Route::get('/quote/{id}', 'ForumController@quote')->name('forum.quote')->middleware('auth');
        Route::post('/quote/post', 'ForumController@quoteStore')->name('forum.quote.store')->middleware('auth');
        Route::get('/create/{id}', 'ForumController@create')->name('forum.create')->middleware('auth');
        Route::post('/create/post', 'ForumController@createStore')->name('forum.create.store')->middleware('auth');
        Route::get('/move/{id}', 'ForumController@move')->name('forum.move')->middleware('auth');
        Route::post('/move/update', 'ForumController@moveUpdate')->name('forum.move.update')->middleware('auth');
        Route::get('/moderate', 'ForumController@moderate')->name('forum.moderate')->middleware('auth');
        Route::get('/edit/{type}/{id}', 'ForumController@edit')->name('forum.edit')->middleware('auth');
        Route::post('/edit/update', 'ForumController@editUpdate')->name('forum.edit.update')->middleware('auth');
    });

    /**
     * Games
     */
    Route::group(['prefix' => 'games'], function() {
        Route::get('/', 'GamesController@index')->name('games.index');
        Route::get('/game/{id}', 'GamesController@show')->name('games.show');
        Route::post('/generate-token', 'GamesController@generateToken')->name('games.generate_token');
    });

    /**
     * Groups
     */
    Route::group(['prefix' => 'groups'], function() {
        Route::get('/', 'GroupsController@index')->name('groups.index');
        Route::get('/{id}', 'GroupsController@show')->name('groups.show');
        Route::get('/{id}/manage', 'GroupsController@edit')->name('groups.edit')->middleware('auth');
        Route::post('/{id}/manage', 'GroupsController@update')->name('groups.update')->middleware('auth');
    });

    /**
     * Users
     */
    Route::get('/users', 'UsersController@index')->name('users.index');
    Route::group(['prefix' => 'profile'], function() {
        Route::get('/{username}', 'UsersController@show')->name('users.profile');
        Route::get('/{username}/friends', 'UsersController@friends')->name('users.friends');
    });

    /**
     * Reports
     */
    Route::group(['prefix' => 'report'], function() {
        Route::get('/{type}/{id}', 'ReportController@index')->name('report.index')->middleware('auth');
        Route::post('/submit', 'ReportController@store')->name('report.store')->middleware('auth');
        Route::get('/thank-you', 'ReportController@thankYou')->name('report.thank_you')->middleware('auth');
    });

    /**
     * Upgrade
     */
    Route::group(['prefix' => 'upgrade'], function() {
        Route::get('/', 'UpgradeController@index')->name('upgrade.index')->middleware('auth');
        Route::get('/{plan}', 'UpgradeController@show')->name('upgrade.show')->middleware('auth');
        Route::post('/paypal-ipn', 'UpgradeController@paypal')->name('upgrade.paypal')->middleware('auth');
    });

    /**
     * Market
     */
    Route::group(['prefix' => 'market'], function() {
        Route::get('/', 'MarketController@index')->name('market.index');
        Route::get('/search', 'MarketController@search')->name('market.search');
        Route::get('/{id}', 'MarketController@show')->name('market.show');
        Route::get('/{id}/edit', 'MarketController@edit')->name('market.edit')->middleware('auth');
        Route::post('/edit', 'MarketController@editUpdate')->name('market.edit.update')->middleware('auth');
        Route::post('/buy', 'MarketController@buy')->name('market.buy')->middleware('auth');
        Route::post('/resell', 'MarketController@resell')->name('market.resell')->middleware('auth');
        Route::post('/favorite', 'MarketController@favorite')->name('market.favorite')->middleware('auth');
        Route::post('/comment', 'MarketController@comment')->name('market.comment')->middleware('auth');
        Route::get('/comment/moderate', 'MarketController@moderateComment')->name('market.comment.moderate')->middleware('auth');
    });

    /**
     * Notes
     */
    Route::get('/notes/{page}', 'NotesController@index')->name('notes');

    /**
     * Promocodes
     */
    // Route::get('/promocodes', 'PromocodesController@index')->name('promocodes.index');
    // Route::post('/promocodes-redeem', 'PromocodesController@redeem')->name('promocodes.redeem');
});
