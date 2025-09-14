<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ForceCreateTables extends Command
{
    protected $signature = 'force:create-tables {--skip-existing}';
    protected $description = 'Force create the new database tables with proper error handling';

    public function handle()
    {
        try {
            $this->info('Starting table creation...');
            
            // Create social_accounts table
            if (!Schema::hasTable('social_accounts')) {
                $this->info('Creating social_accounts table...');
                Schema::create('social_accounts', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('alumni_id');
                    $table->string('platform');
                    $table->string('username')->nullable();
                    $table->string('profile_url')->nullable();
                    $table->boolean('is_verified')->default(false);
                    $table->boolean('is_public')->default(true);
                    $table->json('metadata')->nullable();
                    $table->timestamp('last_updated')->nullable();
                    $table->timestamps();
                    
                    // Add foreign key constraint only if alumni table exists
                    if (Schema::hasTable('alumni')) {
                        $table->foreign('alumni_id')->references('id')->on('alumni')->onDelete('cascade');
                    }
                    
                    $table->unique(['alumni_id', 'platform']);
                    $table->index(['platform', 'is_public']);
                });
                $this->info('✓ social_accounts table created successfully');
            } else {
                $this->info('- social_accounts table already exists');
            }
            
            // Create attachments table
            if (!Schema::hasTable('attachments')) {
                $this->info('Creating attachments table...');
                Schema::create('attachments', function (Blueprint $table) {
                    $table->id();
                    $table->string('attachable_type');
                    $table->unsignedBigInteger('attachable_id');
                    $table->string('file_name');
                    $table->string('original_name');
                    $table->string('file_path');
                    $table->string('file_type');
                    $table->string('mime_type');
                    $table->bigInteger('file_size');
                    $table->string('category')->nullable();
                    $table->text('description')->nullable();
                    $table->boolean('is_public')->default(false);
                    $table->boolean('is_verified')->default(false);
                    $table->timestamp('verified_at')->nullable();
                    $table->unsignedBigInteger('verified_by')->nullable();
                    $table->unsignedBigInteger('uploaded_by');
                    $table->timestamps();
                    
                    // Add foreign key constraints only if users table exists
                    if (Schema::hasTable('users')) {
                        $table->foreign('verified_by')->references('id')->on('users');
                        $table->foreign('uploaded_by')->references('id')->on('users');
                    }
                    
                    $table->index(['attachable_type', 'attachable_id']);
                    $table->index(['category', 'is_public']);
                });
                $this->info('✓ attachments table created successfully');
            } else {
                $this->info('- attachments table already exists');
            }
            
            // Create tracking_statuses table
            if (!Schema::hasTable('tracking_statuses')) {
                $this->info('Creating tracking_statuses table...');
                Schema::create('tracking_statuses', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('alumni_id');
                    $table->string('status_type');
                    $table->text('notes')->nullable();
                    $table->boolean('is_current')->default(false);
                    $table->date('follow_up_date')->nullable();
                    $table->unsignedBigInteger('user_id');
                    $table->timestamps();
                    
                    // Add foreign key constraints only if tables exist
                    if (Schema::hasTable('alumni')) {
                        $table->foreign('alumni_id')->references('id')->on('alumni')->onDelete('cascade');
                    }
                    if (Schema::hasTable('users')) {
                        $table->foreign('user_id')->references('id')->on('users');
                    }
                    
                    $table->index(['alumni_id', 'is_current']);
                    $table->index(['status_type', 'is_current']);
                });
                $this->info('✓ tracking_statuses table created successfully');
            } else {
                $this->info('- tracking_statuses table already exists');
            }
            
            $this->info('Table creation completed successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error creating tables: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}