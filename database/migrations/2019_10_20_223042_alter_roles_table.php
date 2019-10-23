<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('special');
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->string('special')->nullable()->comment('Especial access condition');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('special');
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->enum('special', ['all-access', 'no-access']);
        });
    }
}
