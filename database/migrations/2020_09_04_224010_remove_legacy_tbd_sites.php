<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveLegacyTbdSites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $tbdSites = DB::table('sites')->where('name', 'LIKE', '%TBD%')->get()->pluck('id');
        DB::table('meetings')->whereIn('site_id', $tbdSites)->update(['site_id' => null]);
        DB::table('sites')->where('name', 'LIKE', '%TBD%')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
