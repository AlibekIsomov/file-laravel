<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileContainersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_containers', function (Blueprint $table) {
            $table->id();
            $table->string('upload_path');
            $table->unsignedBigInteger('object_type_id');
            $table->unsignedBigInteger('object_id');
            $table->timestamps();

            $table->foreign('object_type_id')->references('id')->on('object_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_containers');
    }
}
