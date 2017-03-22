<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->uniqid()->comment('菜单标题');
            $table->text('description')->nullabel()->comment('菜单描述');
            $table->string('icon')->nullabel()->comment('图标');
            $table->tinyInteger('parent_id')->nullable()->comment('父标题ID');
            $table->string('uri')->nullbel();
            $table->timestamps();
            // $table->foreign('permissions_id')->references('id')->on('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
