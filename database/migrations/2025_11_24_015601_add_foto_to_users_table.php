<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        // Adding a nullable string column for the filename
        $table->string('foto')->nullable()->after('email'); // Adjust 'after' as needed
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('foto');
    });
   }
};
