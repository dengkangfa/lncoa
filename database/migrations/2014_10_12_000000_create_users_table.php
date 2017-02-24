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
            $table->increments('id')
                    ->comment('用户id');
            $table->string('name')->unique()
                    ->comment('用户名');
            $table->string('nickname')->nullable()
                    ->comment('用户昵称("软件园协会会长")');
            $table->text('avatar')->nullable()
                    ->comment('头像');
            $table->string('email')->unique();
            $table->tinyInteger('status')->default(false)
                    ->comment('激活状态');
            $table->string('password');
            $table->string('description')->nullable()
                    ->comment('用户描述');
            $table->enum('email_notify_enabled', ['yes', 'no'])->default('yes')
                    ->comment('邮箱通知启用状态');
            $table->rememberToken()
                    ->comment('记住我的Token');
            $table->timestamps();
            $table->softDeletes();
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
