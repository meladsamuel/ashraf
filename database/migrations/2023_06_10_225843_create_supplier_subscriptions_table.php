<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('supplier_subscriptions', function (Blueprint $table) {
            $table->id();
        $table->unsignedBigInteger('supplier_id');
        $table->string('plan');
        $table->timestamp('start_date')->nullable();
        $table->timestamp('end_date')->nullable();
        $table->timestamps();

        $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_subscriptions');
    }
};
