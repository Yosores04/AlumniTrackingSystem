<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SystemVersion;
use Illuminate\Support\Facades\Http;

class CheckGitHubConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'github:check {--repo : Also check repository access}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check GitHub API connectivity and rate limits';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking GitHub API connectivity...');
        
        // Get GitHub status
        $status = SystemVersion::checkGitHubStatus();
        
        if ($status['success']) {
            $this->info('✅ Successfully connected to GitHub API');
            
            if ($status['has_token']) {
                $this->info('✅ Using GitHub API token for authentication');
            } else {
                $this->warn('⚠️ No GitHub API token configured. Using unauthenticated requests with lower rate limits.');
                $this->line('   Add GITHUB_API_TOKEN to your .env file to increase rate limits.');
            }
            
            $this->info('📊 Rate Limit Information:');
            $this->line('   - Limit: ' . $status['rate_limit']);
            $this->line('   - Remaining: ' . $status['rate_limit_remaining']);
            
            if ($status['reset_time_formatted']) {
                $this->line('   - Reset at: ' . $status['reset_time_formatted']);
            }
            
            if ($status['rate_limit_remaining'] < 10) {
                $this->warn('⚠️ Rate limit is getting low! Only ' . $status['rate_limit_remaining'] . ' requests remaining.');
            }
            
            // Check repository access if requested
            if ($this->option('repo')) {
                $this->checkRepositoryAccess();
            }
            
            return Command::SUCCESS;
        } else {
            $this->error('❌ Failed to connect to GitHub API');
            $this->error('Error: ' . $status['message']);
            
            return Command::FAILURE;
        }
    }
    
    /**
     * Check if we can access the repository configured in SystemVersionController
     */
    protected function checkRepositoryAccess()
    {
        $this->info('Checking repository access...');
        
        // Get repository info from the SystemVersionController
        $controller = new \App\Http\Controllers\SystemVersionController();
        $repoOwner = $controller->githubOwner;
        $repoName = $controller->githubRepo;
        
        if (empty($repoOwner) || empty($repoName)) {
            $this->error('❌ Repository owner or name is not configured in SystemVersionController.');
            return;
        }
        
        $this->line("Repository: {$repoOwner}/{$repoName}");
        
        // Setup GitHub API headers
        $headers = ['User-Agent' => 'Alumni-Tracking-System-Updater'];
        
        // Add token if available
        $token = config('services.github.token');
        if ($token) {
            $headers['Authorization'] = 'token ' . $token;
        }
        
        try {
            // Check if the repository exists and is accessible
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->get("https://api.github.com/repos/{$repoOwner}/{$repoName}");
            
            if ($response->successful()) {
                $repoData = $response->json();
                $this->info('✅ Repository is accessible');
                $this->line('   - Full name: ' . ($repoData['full_name'] ?? 'N/A'));
                $this->line('   - Description: ' . ($repoData['description'] ?? 'No description'));
                $this->line('   - Visibility: ' . ($repoData['visibility'] ?? 'Unknown'));
                $this->line('   - Default branch: ' . ($repoData['default_branch'] ?? 'Unknown'));
                
                // Check releases
                $this->checkReleases($repoOwner, $repoName, $headers);
                
                // Check tags
                $this->checkTags($repoOwner, $repoName, $headers);
            } else {
                $this->error('❌ Could not access repository');
                $this->error('Status code: ' . $response->status());
                $this->error('Response: ' . $response->body());
            }
        } catch (\Exception $e) {
            $this->error('❌ Error checking repository access: ' . $e->getMessage());
        }
    }
    
    /**
     * Check if the repository has releases
     */
    protected function checkReleases($owner, $repo, $headers)
    {
        $this->info('Checking releases...');
        
        try {
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->get("https://api.github.com/repos/{$owner}/{$repo}/releases");
            
            if ($response->successful()) {
                $releases = $response->json();
                
                if (count($releases) > 0) {
                    $this->info('✅ Found ' . count($releases) . ' releases');
                    
                    // Display the latest 3 releases
                    $count = 0;
                    foreach ($releases as $release) {
                        if ($count >= 3) break;
                        $this->line('   - ' . ($release['tag_name'] ?? 'Unnamed') . ': ' . ($release['name'] ?? 'No name'));
                        $count++;
                    }
                } else {
                    $this->warn('⚠️ No releases found in the repository');
                }
            } else {
                $this->error('❌ Failed to check releases');
                $this->error('Status code: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('❌ Error checking releases: ' . $e->getMessage());
        }
    }
    
    /**
     * Check if the repository has tags
     */
    protected function checkTags($owner, $repo, $headers)
    {
        $this->info('Checking tags...');
        
        try {
            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->get("https://api.github.com/repos/{$owner}/{$repo}/tags");
            
            if ($response->successful()) {
                $tags = $response->json();
                
                if (count($tags) > 0) {
                    $this->info('✅ Found ' . count($tags) . ' tags');
                    
                    // Display the latest 3 tags
                    $count = 0;
                    foreach ($tags as $tag) {
                        if ($count >= 3) break;
                        $this->line('   - ' . ($tag['name'] ?? 'Unnamed'));
                        $count++;
                    }
                } else {
                    $this->warn('⚠️ No tags found in the repository');
                }
            } else {
                $this->error('❌ Failed to check tags');
                $this->error('Status code: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('❌ Error checking tags: ' . $e->getMessage());
        }
    }
} 