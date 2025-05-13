<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Stancl\Tenancy\Facades\Tenancy;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create {name} {domain} {--debug : Show debug information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tenant with a domain';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $domain = $this->argument('domain');
        $debug = $this->option('debug');

        $this->info("Creating tenant '{$name}' with domain '{$domain}'");

        // Check if tenant already exists
        if (Tenant::find($name)) {
            $this->warn("Tenant with ID '{$name}' already exists!");
            if ($this->confirm('Do you want to recreate this tenant?', false)) {
                Tenant::find($name)->delete();
                $this->info("Tenant '{$name}' deleted. Creating new tenant...");
            } else {
                return Command::FAILURE;
            }
        }

        // Create tenant and domain
        $tenant = Tenant::create(['id' => $name]);
        $tenant->domains()->create(['domain' => $domain]);

        $this->info("Tenant created successfully!");
        $this->info("Tenant ID: {$tenant->id}");
        $this->info("Domain: {$domain}");
        
        if ($debug) {
            // Verify central domains config
            $this->info("Central domains: " . implode(', ', config('tenancy.central_domains')));
            
            // Verify tenant database exists
            try {
                $dbName = config('tenancy.database.prefix') . $name . config('tenancy.database.suffix');
                $this->info("Tenant database name: {$dbName}");
            } catch (\Exception $e) {
                $this->error("Error checking database: " . $e->getMessage());
            }

            // Verify routes are registered
            $this->info("Accessing tenant through: http://{$domain}:8000");
            $this->info("If this doesn't work, try the following:");
            $this->info("1. Check that you've added '{$domain}' to your hosts file");
            $this->info("2. Run the server with: php artisan serve --host=0.0.0.0");
            $this->info("3. Make sure your tenant routes are defined in routes/tenant.php");
        }
        
        return Command::SUCCESS;
    }
}
