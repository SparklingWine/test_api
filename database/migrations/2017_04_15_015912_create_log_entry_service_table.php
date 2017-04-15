<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogEntryServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_entry_service', function (Blueprint $table) {
            $table->integer('log_entry_id')->unsigned()->nullable();
            $table->foreign('log_entry_id')->references('id')
                ->on('log_entries')->onDelete('cascade');

            $table->integer('service_id')->unsigned()->nullable();
            $table->foreign('service_id')->references('id')
                ->on('services')->onDelete('cascade');
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
        Schema::dropIfExists('log_entry_service');
    }
}
