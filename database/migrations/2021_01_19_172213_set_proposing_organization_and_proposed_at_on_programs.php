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
        $exampleId = DB::table('organizations')->where('name', 'Exampleville Parks & Recreation')->first()->id;
        $techsplosionId = DB::table('organizations')->where('name', 'Techsplosion')->first()->id;
        if (!empty($exampleId) && !empty($techsplosionId)) {
            DB::table('programs')->where('created_at', '<=', '2020-02-09 22:30:52')->where('name', 'Example Cooking Camp')->update([
                'proposed_at' => '2020-02-09 22:30:52',
                'proposing_organization_id' => $exampleId
            ]);
            DB::table('programs')->where('created_at', '<=', '2020-02-09 22:30:52')->where('name', '!=', 'Example Cooking Camp')->update([
                'proposed_at' => '2020-02-09 22:30:52',
                'proposing_organization_id' => $techsplosionId
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
