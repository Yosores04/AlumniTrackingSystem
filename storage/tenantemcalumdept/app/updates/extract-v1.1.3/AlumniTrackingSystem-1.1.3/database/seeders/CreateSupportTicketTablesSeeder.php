<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;

class CreateSupportTicketTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            // Create support_tickets table if it doesn't exist
            if (!Schema::hasTable('support_tickets')) {
                Schema::create('support_tickets', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id')->constrained();
                    $table->string('subject');
                    $table->text('description');
                    $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open');
                    $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                    $table->string('attachment_path')->nullable();
                    $table->timestamps();
                });

                $this->command->info('Support Tickets table created successfully.');
            } else {
                $this->command->info('Support Tickets table already exists.');
            }

            // Create ticket_responses table if it doesn't exist
            if (!Schema::hasTable('ticket_responses')) {
                Schema::create('ticket_responses', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('support_ticket_id')->constrained()->cascadeOnDelete();
                    $table->foreignId('user_id')->constrained();
                    $table->text('message');
                    $table->string('attachment_path')->nullable();
                    $table->boolean('is_staff_reply')->default(false);
                    $table->timestamps();
                });

                $this->command->info('Ticket Responses table created successfully.');
            } else {
                $this->command->info('Ticket Responses table already exists.');
            }
        } catch (\Exception $e) {
            $this->command->error('Error creating tables: ' . $e->getMessage());
            Log::error('Error in CreateSupportTicketTablesSeeder: ' . $e->getMessage());
        }
    }
} 