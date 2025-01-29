<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropObjectTypeFromFileContainersTable extends Migration
{
    public function up()
    {
        Schema::table('file_containers', function (Blueprint $table) {
            $table->dropColumn('object_type');
        });
    }

    public function down()
    {
        Schema::table('file_containers', function (Blueprint $table) {
            $table->string('object_type')->nullable();
        });
    }
}
