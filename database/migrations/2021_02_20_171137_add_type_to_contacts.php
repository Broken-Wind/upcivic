<?php

use App\Participant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToContacts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            //
            $table->string('type')->nullable();
        });
        Participant::all()->each(function ($participant) {
            $participant->contacts->whereNull('type')->each(function ($contact) {
                if (!empty($contact->email)) {
                    $contact->pivot->type = 'primary';
                    $contact->pivot->save();
                    return;
                }
                $contact->pivot->type = 'alternate';
                $contact->pivot->save();
                return;
            });
        });
        // Schema::table('contacts', function (Blueprint $table) {
        //     //
        //     $table->string('type')->nullable(false)->change;
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts', function (Blueprint $table) {
            //
            $table->dropColumn('type');
        });
    }
}
