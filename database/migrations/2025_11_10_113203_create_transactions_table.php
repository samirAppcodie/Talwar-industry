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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('admin_id');
            $table->enum('transaction_type', ['deposit', 'withdraw_flour', 'withdraw_cash', 'grinding', 'balance_adjustment']);
            $table->dateTime('transaction_date');
            $table->decimal('wheat_in_kg', 10, 2)->default(0);
            $table->decimal('wheat_out_kg', 10, 2)->default(0);
            $table->decimal('grinding_charges_per_kg', 10, 2)->default(0);
            $table->decimal('grinding_total_charge', 10, 2)->default(0);
            $table->decimal('cash_in', 10, 2)->default(0);
            $table->decimal('cash_out', 10, 2)->default(0);
            $table->decimal('balance_wheat_after', 10, 2)->default(0);
            $table->decimal('balance_cash_after', 10, 2)->default(0);
            $table->decimal('weight_at_entry', 10, 2)->default(0);
            $table->decimal('weight_at_exit', 10, 2)->nullable();
            $table->boolean('card_tapped')->default(false);
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
