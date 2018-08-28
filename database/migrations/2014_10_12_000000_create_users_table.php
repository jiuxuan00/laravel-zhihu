<?php

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
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('confirmation_token', 100);
            $table->string('email', 150)->unique()->comment('邮箱');
            $table->string('password')->comment('密码');
            $table->string('avatar')->comment('头像');
            $table->smallInteger('is_active')->default(0)->comment('');;
            $table->string('questions_count')->default(0)->comment('提问数量');
            $table->string('answers_count')->default(0)->comment('回答数量');
            $table->string('comments_count')->default(0)->comment('评论数量');
            $table->string('favorites_count')->default(0)->comment('收藏数量');
            $table->string('likes_count')->default(0)->comment('点赞数量');
            $table->string('followers_count')->default(0)->comment('关注数量');
            $table->string('followings_count')->default(0)->comment('被关注数量');
            $table->json('settings')->nullable(); //报错是应为json在mysql5.7以上才可用
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
