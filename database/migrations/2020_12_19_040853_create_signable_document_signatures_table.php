<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignableDocumentSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signable_document_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('signature');
            $table->string('ip');
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->foreign('document_id')->references('id')->on('signable_document_assignments')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('SET NULL');
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
        Schema::dropIfExists('signable_document_signatures');
    }
}
