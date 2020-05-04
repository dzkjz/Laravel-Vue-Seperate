<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddedCafeParentChildRelationship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cafes', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('parent')->nullable()->after('id');
            $table->string('location_name')->after('name');
            $table->integer('roaster')->after('longitude');
            $table->text('website')->after('roaster');
            $table->text('description')->after('website');
            $table->unsignedBigInteger('added_by')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cafes', function (Blueprint $table) {
            //
            $table->dropColumn('parent');
            $table->dropColumn('location_name');
            $table->dropColumn('roaster');
            $table->dropColumn('website');
            $table->dropColumn('description');
            $table->dropColumn('added_by');
        });
    }
}
