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

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('ip')->nullable(); // Null incase it fails
            $table->text('description')->nullable();
            $table->string('signature')->nullable();
            $table->integer('currency_coins')->default(10);
            $table->integer('currency_cash')->default(0);
            $table->timestamp('next_currency_payout')->nullable();
            $table->boolean('banned')->default(false);
            $table->integer('vip')->default(0);
            $table->timestamp('vip_until')->nullable();
            $table->boolean('verified')->default(false);
            $table->boolean('beta_tester')->default(false);
            $table->integer('power')->default(0);
            $table->timestamp('flood')->nullable();
            $table->string('setting_message')->default('everyone');
            $table->string('setting_friend')->default('everyone');
            $table->string('setting_trade')->default('everyone');
            $table->string('setting_inventory')->default('everyone');
            $table->string('avatar_url')->default('img/avatars/BUHuVgds3QM869RpKrjLcIhNx.png');
            $table->string('headshot_url')->default('img/headshots/BUHuVgds3QM869RpKrjLcIhNx.png');
            $table->string('rgb_head')->default('255/255,255/255,255/255');
            $table->string('rgb_torso')->default('125/255,125/255,125/255');
            $table->string('rgb_left_arm')->default('255/255,255/255,255/255');
            $table->string('rgb_right_arm')->default('255/255,255/255,255/255');
            $table->string('rgb_left_leg')->default('255/255,255/255,255/255');
            $table->string('rgb_right_leg')->default('255/255,255/255,255/255');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
