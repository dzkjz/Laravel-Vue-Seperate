<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCafesPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cafes_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cafe_id');
            $table->unsignedBigInteger('uploaded_by');
            $table->text('file_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cafes_photos');
    }
}
