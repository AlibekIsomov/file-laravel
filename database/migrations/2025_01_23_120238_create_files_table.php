<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('file_name');
            $table->string('original_name');
            $table->integer('size');
            $table->string('mime_type');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
