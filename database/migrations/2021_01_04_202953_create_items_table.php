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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('creator_id')->unsigned();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price_coins');
            $table->integer('price_cash');
            $table->integer('type');
            $table->string('status')->default('pending'); // declined, pending, accepted
            $table->boolean('onsale');
            $table->boolean('public_view')->default(true);
            $table->boolean('timed_item')->default(false);
            $table->timestamp('offsale_at')->nullable();
            $table->boolean('collectible')->default(false);
            $table->integer('collectible_stock')->nullable();
            $table->integer('collectible_stock_original')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->timestamps();
        });

        Schema::table('items', function (Blueprint $table) {
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('hat1')->unsigned()->nullable();
            $table->bigInteger('hat2')->unsigned()->nullable();
            $table->bigInteger('hat3')->unsigned()->nullable();
            $table->bigInteger('face')->unsigned()->nullable();
            $table->bigInteger('accessory')->unsigned()->nullable();
            $table->bigInteger('tshirt')->unsigned()->nullable();
            $table->bigInteger('shirt')->unsigned()->nullable();
            $table->bigInteger('pants')->unsigned()->nullable();
            $table->bigInteger('head')->unsigned()->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('hat1')->references('id')->on('items')->onDelete('set null');
            $table->foreign('hat2')->references('id')->on('items')->onDelete('set null');
            $table->foreign('hat3')->references('id')->on('items')->onDelete('set null');
            $table->foreign('face')->references('id')->on('items')->onDelete('set null');
            $table->foreign('accessory')->references('id')->on('items')->onDelete('set null');
            $table->foreign('tshirt')->references('id')->on('items')->onDelete('set null');
            $table->foreign('shirt')->references('id')->on('items')->onDelete('set null');
            $table->foreign('pants')->references('id')->on('items')->onDelete('set null');
            $table->foreign('head')->references('id')->on('items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
