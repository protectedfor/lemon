<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->date('quarter_from');
            $table->date('quarter_to');
            $table->unsignedSmallInteger('year');
            $table->tinyText('type');
            $table->tinyText('status');

            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');

            /**
             * Налог с продаж
             */
//            Подраздел А
            $table->tinyInteger('field_050')->nullable();
            $table->tinyInteger('field_051')->nullable();
            $table->tinyInteger('field_052')->nullable();
            $table->tinyInteger('field_053')->nullable();
            $table->tinyInteger('field_054')->nullable();
            $table->tinyInteger('field_055')->nullable();
            $table->tinyInteger('field_056')->nullable();
            $table->tinyInteger('field_057')->nullable();
            $table->tinyInteger('field_058')->nullable();
            $table->tinyInteger('field_059')->nullable();
            $table->tinyInteger('field_060')->nullable();
            $table->tinyInteger('field_061')->nullable();
            $table->tinyInteger('field_062')->nullable();
//            END Подраздел А

//            Подраздел Б
            $table->tinyInteger('field_063')->nullable();
            $table->tinyInteger('field_064')->nullable();
            $table->tinyInteger('field_065')->nullable();
            $table->tinyInteger('field_066')->nullable();
            $table->tinyInteger('field_067')->nullable();
            $table->tinyInteger('field_068')->nullable();
            $table->tinyInteger('field_069')->nullable();
            $table->tinyInteger('field_070')->nullable();
            $table->tinyInteger('field_071')->nullable();
            $table->tinyInteger('field_072')->nullable();
            $table->tinyInteger('field_073')->nullable();
            $table->tinyInteger('field_074')->nullable();
            $table->tinyInteger('field_075')->nullable();
//            END Подраздел Б

//            Подраздел В
            $table->tinyInteger('field_076')->nullable();
            $table->tinyInteger('field_077')->nullable();
            $table->tinyInteger('field_078')->nullable();
            $table->tinyInteger('field_079')->nullable();
            $table->tinyInteger('field_080')->nullable();
            $table->tinyInteger('field_081')->nullable();
            $table->tinyInteger('field_082')->nullable();
            $table->tinyInteger('field_083')->nullable();
//            END Подраздел В

//            Подраздел Г
            $table->tinyInteger('field_084')->nullable();
            $table->tinyInteger('field_085')->nullable();
//            END Подраздел Г
            /** END Налог с продаж */

            /**
             * Подоходный налог
             */
            $table->tinyInteger('field_086')->nullable();
            $table->tinyInteger('field_087')->nullable();
            $table->tinyInteger('field_088')->nullable();
            $table->tinyInteger('field_089')->nullable();
            $table->tinyInteger('field_090')->nullable();
            $table->tinyInteger('field_091')->nullable();
            $table->tinyInteger('field_092')->nullable();
            $table->tinyInteger('field_093')->nullable();
            $table->tinyInteger('field_094')->nullable();
            $table->tinyInteger('field_095')->nullable();
            $table->tinyInteger('field_096')->nullable();
            $table->tinyInteger('field_097')->nullable();
            $table->tinyInteger('field_098')->nullable();
            $table->tinyInteger('field_099')->nullable();
            /** END Подоходный налог */

            /**
             * Налог на прибыль
             */
            $table->tinyInteger('field_203')->nullable();
            $table->tinyInteger('field_204')->nullable();
            $table->tinyInteger('field_205')->nullable();
            $table->tinyInteger('field_206')->nullable();
            $table->tinyInteger('field_207')->nullable();
            $table->tinyInteger('field_208')->nullable();
            $table->tinyInteger('field_209')->nullable();
            $table->tinyInteger('field_210')->nullable();
            $table->tinyInteger('field_211')->nullable();
            $table->tinyInteger('field_212')->nullable();
            $table->tinyInteger('field_213')->nullable();
            $table->tinyInteger('field_214')->nullable();
            $table->tinyInteger('field_215')->nullable();
            /** END Налог на прибыль */

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
        Schema::dropIfExists('reports');
    }
}
