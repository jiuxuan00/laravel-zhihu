<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('body');
            $table->integer('user_id')->unsigned()->comment('用户id');
            $table->integer('comment_count')->default(0)->comment('评论数量');
            $table->integer('followers_count')->default(1)->comment('关注数量');
            $table->integer('answers_count')->default(0)->comment('回答数量');
            $table->string('close_comment',8)->default('F')->comment('是否是关闭问题状态，F代表false');
            $table->string('is_hidden',8)->default('')->comment('是否是隐藏问题状态F，F代表false');
            $table->timestamp();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}
