<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePrimaryKeyOnLogEntryServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_entry_service', function (Blueprint $table) {
            $table->primary(['log_entry_id', 'service_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_entry_service', function (Blueprint $table) {
            # Выглядит странно, но иначе первичный композитный ключ не удалить.
            $table->dropForeign(['log_entry_id']);
            $table->dropForeign(['service_id']);
            $table->dropPrimary('log_entry_service_log_entry_id_service_id_primary');
            $table->foreign('log_entry_id')->references('id')
                ->on('log_entries')->onDelete('cascade');
            $table->foreign('service_id')->references('id')
                ->on('services')->onDelete('cascade');
        });
    }
}
