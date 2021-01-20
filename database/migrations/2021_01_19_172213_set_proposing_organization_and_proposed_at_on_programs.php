<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetProposingOrganizationAndProposedAtOnPrograms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $example = DB::table('organizations')->where('name', 'Exampleville Parks & Recreation')->first();
        $techsplosion = DB::table('organizations')->where('name', 'Techsplosion')->first();
        if (!empty($example) && !empty($techsplosion)) {
            DB::table('programs')->where('created_at', '<=', '2020-02-09 22:30:52')->where('name', 'Example Cooking Camp')->update([
                'proposed_at' => '2020-02-09 22:30:52',
                'proposing_organization_id' => $example->id
            ]);
            DB::table('programs')->where('created_at', '<=', '2020-02-09 22:30:52')->where('name', '!=', 'Example Cooking Camp')->update([
                'proposed_at' => '2020-02-09 22:30:52',
                'proposing_organization_id' => $techsplosion->id
            ]);
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
        DB::table('programs')->where('created_at', '<=', '2020-02-09 22:30:52')->update([
            'proposed_at' => null,
            'proposing_organization_id' => null
        ]);
    }
}
