<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImporterLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importer_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->nullable();
            $table->timestamp('run_at')->useCurrent();
            $table->integer('entries_processed'); 
            $table->integer('entries_created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('importer__logs');
    }
}
