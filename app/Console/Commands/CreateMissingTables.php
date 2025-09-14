<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMissingTables extends Command
{
    protected $signature = 'create:missing-tables';
    protected $description = 'Manually create the missing tables for database refactoring';

    public function handle()
    {
        $this->info('Creating social_accounts table...');
        $this->createSocialAccountsTable();
        
        $this->info('Creating attachments table...');
        $this->createAttachmentsTable();
        
        $this->info('Creating tracking_statuses table...');
        $this->createTrackingStatusesTable();
        
        $this->info('All tables created successfully!');
        return 0;
    }
    
    private function createSocialAccountsTable()
    {
        if (!Schema::hasTable('social_accounts')) {
            Schema::create('social_accounts', function ($table) {
                $table->id();
                $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
                $table->string('platform');
                $table->string('username')->nullable();
                $table->string('profile_url')->nullable();
                $table->boolean('is_verified')->default(false);
                $table->boolean('is_public')->default(true);
                $table->json('metadata')->nullable();
                $table->timestamp('last_updated')->nullable();
                $table->timestamps();
                
                $table->unique(['alumni_id', 'platform']);
                $table->index(['platform', 'is_public']);
            });
            $this->info('✓ social_accounts table created');
        } else {
            $this->info('✓ social_accounts table already exists');
        }
    }
    
    private function createAttachmentsTable()
    {
        if (!Schema::hasTable('attachments')) {
            Schema::create('attachments', function ($table) {
                $table->id();
                $table->morphs('attachable');
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
                $table->foreignId('verified_by')->nullable()->constrained('users');
                $table->foreignId('uploaded_by')->constrained('users');
                $table->timestamps();
                
                $table->index(['attachable_type', 'attachable_id']);
                $table->index(['category', 'is_public']);
            });
            $this->info('✓ attachments table created');
        } else {
            $this->info('✓ attachments table already exists');
        }
    }
    
    private function createTrackingStatusesTable()
    {
        if (!Schema::hasTable('tracking_statuses')) {
            Schema::create('tracking_statuses', function ($table) {
                $table->id();
                $table->foreignId('alumni_id')->constrained('alumni')->onDelete('cascade');
                $table->string('status_type');
                $table->text('notes')->nullable();
                $table->boolean('is_current')->default(false);
                $table->date('follow_up_date')->nullable();
                $table->foreignId('user_id')->constrained('users');
                $table->timestamps();
                
                $table->index(['alumni_id', 'is_current']);
                $table->index(['status_type', 'is_current']);
            });
            $this->info('✓ tracking_statuses table created');
        } else {
            $table->info('✓ tracking_statuses table already exists');
        }
    }
}