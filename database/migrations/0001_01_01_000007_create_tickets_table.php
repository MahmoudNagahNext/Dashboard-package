<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use nextdev\nextdashboard\Enums\TicketPriorityEnum;
use nextdev\nextdashboard\Enums\TicketStatusEnum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            
            $table->enum('status', array_column(TicketStatusEnum::cases(), 'value'))
                ->default(TicketStatusEnum::OPEN->value);
            $table->enum('priority', array_column(TicketPriorityEnum::cases(), 'value'))
                ->default(TicketPriorityEnum::MEDIUM->value);


            $table->foreignId('category_id')
                ->nullable()
                ->constrained('ticket_categories')
                ->onDelete('cascade');

            $table->morphs('creator');
            $table->nullableMorphs('assignee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
