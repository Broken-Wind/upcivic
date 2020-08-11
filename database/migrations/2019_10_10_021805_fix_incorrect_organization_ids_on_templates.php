<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixIncorrectOrganizationIdsOnTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $templates = DB::table('templates')->get();

        foreach ($templates as $template) {
            $correctOrganizationId = DB::table('tenants')->find($template->organization_id)->organization_id;
            DB::table('templates')
                ->where('id', $template->id)
                ->update(['organization_id' => $correctOrganizationId]);
        }
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
