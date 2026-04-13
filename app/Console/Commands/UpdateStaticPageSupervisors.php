<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StaticPage;
use Illuminate\Support\Facades\Storage;

class UpdateStaticPageSupervisors extends Command
{
    protected $signature = 'static-pages:update-supervisors {--file=static_pages_export.json}';
    protected $description = 'Update static pages with supervisor data from JSON export file';

    public function handle()
    {
        $fileName = $this->option('file');
        $filePath = base_path($fileName);
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Reading supervisor data from: {$fileName}");
        
        $jsonContent = file_get_contents($filePath);
        $pagesData = json_decode($jsonContent, true);
        
        if (!$pagesData) {
            $this->error("Invalid JSON content in file: {$fileName}");
            return 1;
        }

        $this->info("Found " . count($pagesData) . " pages in JSON file");
        
        $updated = 0;
        $skipped = 0;
        
        foreach ($pagesData as $pageData) {
            if (!isset($pageData['id'])) {
                $this->warn("Skipping page without ID");
                $skipped++;
                continue;
            }
            
            $page = StaticPage::find($pageData['id']);
            
            if (!$page) {
                $this->warn("Page with ID {$pageData['id']} not found in database");
                $skipped++;
                continue;
            }
            
            // Check if this page has supervisor data
            $supervisorName = $pageData['supervisor_name'] ?? null;
            $supervisorTitle = $pageData['supervisor_title'] ?? null;
            $supervisorDescription = $pageData['supervisor_description'] ?? null;
            $supervisorAvatar = $pageData['supervisor_avatar'] ?? null;
            
            // Skip if no supervisor data
            if (empty($supervisorName) && empty($supervisorTitle) && empty($supervisorDescription) && empty($supervisorAvatar)) {
                $this->warn("Page '{$page->title}' has no supervisor data - skipping");
                $skipped++;
                continue;
            }
            
            // Update the page with supervisor data
            $page->supervisor_name = $supervisorName;
            $page->supervisor_title = $supervisorTitle;
            $page->supervisor_description = $supervisorDescription;
            $page->supervisor_avatar = $supervisorAvatar;
            
            $page->save();
            
            $this->info("✓ Updated page: {$page->title}");
            $this->line("  - Supervisor: {$supervisorName}");
            $this->line("  - Title: {$supervisorTitle}");
            $this->line("  - Avatar: {$supervisorAvatar}");
            
            $updated++;
        }
        
        $this->info("\nSummary:");
        $this->info("- Updated: {$updated} pages");
        $this->info("- Skipped: {$skipped} pages");
        
        if ($updated > 0) {
            $this->info("\n✅ Successfully updated static page supervisor data!");
            
            // Check avatar file accessibility
            $this->info("\nChecking supervisor avatar accessibility...");
            $this->checkAvatarAccessibility();
        }
        
        return 0;
    }
    
    private function checkAvatarAccessibility()
    {
        $pages = StaticPage::whereNotNull('supervisor_avatar')->get();
        
        foreach ($pages as $page) {
            if (!$page->supervisor_avatar) {
                continue;
            }
            
            $avatarPath = $page->supervisor_avatar;
            
            // Check if file exists in storage
            if (Storage::disk('public')->exists($avatarPath)) {
                $this->info("✓ Avatar accessible: {$avatarPath}");
            } else {
                $this->warn("✗ Avatar not found: {$avatarPath}");
                
                // Try to find alternative paths
                $fileName = basename($avatarPath);
                $possiblePaths = [
                    "supervisors/{$fileName}",
                    "images/supervisors/{$fileName}",
                    "avatars/{$fileName}",
                ];
                
                $found = false;
                foreach ($possiblePaths as $altPath) {
                    if (Storage::disk('public')->exists($altPath)) {
                        $this->info("  → Found alternative: {$altPath}");
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    $this->error("  → No alternative path found for {$fileName}");
                }
            }
        }
    }
}