<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('organization_title')->nullable();
            $table->string('director_name')->nullable();
            $table->string('inn', 12);
            $table->string('okpo')->nullable();
            $table->string('address')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bik')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('logo')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['inn', 'user_id']);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations');
    }
}
