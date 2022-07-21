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

class CreateForumTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $name = config('app.name');

        Schema::create('forum_topics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->timestamp('last_post_at')->nullable();
            $table->timestamps();
        });

        DB::table('forum_topics')->insert([
            [
                'name' => "{$name} Central",
                'description' => "This is the general discussion for {$name}. You should post topics relating to {$name} features and news here."
            ],
            [
                'name' => 'Off Topic',
                'description' => 'If there\'s no other subforum that suits the content you want to post, post it here!'
            ],
            [
                'name' => 'Marketplace',
                'description' => 'Are you interested in trading and/or selling your accessories? This is the place for you!'
            ],
            [
                'name' => 'Website Suggestions',
                'description' => "Do you have an idea for {$name}? Post your suggestions here."
            ],
            [
                'name' => 'Group Discussion',
                'description' => 'This is the place for all your group discussions, be it declarations of war on other groups or simple instructions on serving a customer their coffee in your Cafe group.'
            ],
            [
                'name' => 'Technical Support',
                'description' => 'If you have questions relating to your account, you can seek assistance in this sub-forum.'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forum_topics');
    }
}
