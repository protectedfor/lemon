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
            $table->string('phone', 15)->after('name');
            $table->string('password')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('organization_title')->nullable()->after('name');
            $table->unique(['phone', 'email']);
            $table->dropColumn('name');
            $table->string('director_name')->nullable()->after('id');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->string('confirmation_code', 6)->nullable()->after('phone_verified_at');
            $table->string('inn', 12)->nullable()->after('organization_title');
            $table->string('okpo')->nullable()->after('inn');
            $table->string('address')->nullable()->after('okpo');
            $table->string('account_number')->nullable()->after('address');
            $table->string('bik')->nullable()->after('account_number');
            $table->string('bank_address')->nullable()->after('bik');
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
