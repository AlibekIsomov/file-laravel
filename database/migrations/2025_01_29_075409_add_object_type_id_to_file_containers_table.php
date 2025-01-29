<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObjectTypeIdToFileContainersTable extends Migration
{
    public function up()
    {
        Schema::table('file_containers', function (Blueprint $table) {
            $table->unsignedBigInteger('object_type_id')->after('upload_path');
            $table->foreign('object_type_id')->references('id')->on('object_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('file_containers', function (Blueprint $table) {
            $table->dropForeign(['object_type_id']);
            $table->dropColumn('object_type_id');
        });
    }
}
