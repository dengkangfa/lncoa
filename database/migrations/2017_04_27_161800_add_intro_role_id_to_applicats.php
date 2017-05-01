<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIntroRoleIdToApplicats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applicats', function(Blueprint $table) {
              $table->integer('role_id')->unsigned()->nullable()->after('files');
              $table->foreign('role_id')->references('id')->on('roles')
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
        Schema::table('applicats', function (Blueprint $table) {
              $table->dropForeign('applicats_role_id_foreign');
              $table->dropColumn('role_id');
        });
    }
}
