<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndMetadataToTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
            $table->string('type')->nullable();
            $table->json('metadata')->nullable();
        });

        DB::table('tasks')->update(['type' => 'generic_assignment']);

        Schema::table('tasks', function (Blueprint $table) {
            $table->string('type')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            //
            $table->dropColumn('type');
            $table->dropColumn('metadata');
        });
    }
}
