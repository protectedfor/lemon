<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('organization_title')->nullable();
            $table->string('director_name')->nullable();
            $table->string('inn', 12);
            $table->string('okpo')->nullable();
            $table->string('address')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bik')->nullable();
            $table->string('bank_address')->nullable();
            $table->unsignedBigInteger('organization_id');
            $table->timestamps();

            $table->unique(['inn', 'organization_id']);

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partners');
    }
}
