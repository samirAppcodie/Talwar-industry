<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add mobile column - adjust as needed
            $table->string('mobile', 20)->nullable()->after('email');

            // Optional: make mobile numbers unique
            $table->unique('mobile');

            // Optional: add an index for faster lookups
            // $table->index('mobile');
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
            // Drop the unique constraint first (if it exists
            $table->dropUnique(['mobile']);

            // Then drop the column
            $table->dropColumn('mobile');
        });
    }
};