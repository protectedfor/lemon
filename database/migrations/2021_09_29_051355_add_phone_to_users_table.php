<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->string('email')->nullable()->change();
            $table->string('phone', 15)->unique()->after('name');
            $table->timestamp('code_requested_at')->nullable()->after('phone');
            $table->string('confirmation_code', 6)->nullable()->after('code_requested_at');
            $table->string('password')->nullable()->change();
            $table->string('name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->change();
            $table->string('password')->change();
            $table->dropUnique(['phone', 'email']);
            $table->string('name')->after('id');
            $table->dropColumn([
                'director_name', 'phone_verified_at', 'confirmation_code', 'organization_title', 'phone',
                'inn', 'okpo', 'address', 'account_number', 'bik', 'bank_address',
            ]);
        });
    }
}
