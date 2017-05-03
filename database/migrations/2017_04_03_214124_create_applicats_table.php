<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('提交用户');
            $table->string('principal')->comment('责任人');
            $table->char('mobile',11)->comment('联系方式');
            $table->integer('mechanism_id')->unsigned()->comment('申请机构');
            $table->smallInteger('number')->comment('参与人数');
            $table->integer('type_id')->unsigned()->comment('类型');
            $table->dateTime('startTime')->comment('开始时间');
            $table->dateTime('endTime')->comment('结束时间');
            $table->string('agency')->nullable()->comment('联合机构');
            $table->text('reason')->comment('申请缘由');
            $table->text('goods')->nullable()->comment('物资申请');
            $table->text('files')->nullable();
            $table->tinyInteger('stage')->nullable()->default(0)->comment('阶段');
            $table->integer('status_id')->default(1)->unsigned()->comment('状态');
            $table->timestamps();

            $table->foreign('mechanism_id')->references('id')->on('mechanisms')
                ->onUpdaye('cascade')->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applicats');
    }
}
