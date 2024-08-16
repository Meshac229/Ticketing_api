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
        Schema::disableForeignKeyConstraints();

        Schema::create('tickets', function (Blueprint $table) {
            $table->id('ticket_id');
            $table->unsignedBigInteger('ticket_event_id');
            $table->string('ticket_email', 255);
            $table->string('ticket_phone', 20)->nullable();
            $table->mediumInteger('ticket_price');
            $table->unsignedBigInteger('ticket_order_id');
            $table->string('ticket_key', 100)->unique();
            $table->unsignedBigInteger('ticket_ticket_type_id');
            $table->enum('ticket_status', ['active', 'validated', 'expired', 'cancelled']);
            $table->timestamp('ticket_created_on')->useCurrent();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
