<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasCollaboratorsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaboratables', function (Blueprint $table) {
            $table->id();
            $table->string('collaborator_type')->nullable();
            $table->unsignedBigInteger('collaborator_id')->nullable();
            $table->string('collaboratable_type')->nullable();
            $table->unsignedBigInteger('collaboratable_id')->nullable();
            $table->boolean('is_owner')->default(false);

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
        Schema::dropIfExists('collaboratables');
    }
}
