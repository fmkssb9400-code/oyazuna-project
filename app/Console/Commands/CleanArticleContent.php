<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;

class CleanArticleContent extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'article:clean-content 
                           {--dry-run : Show what would be cleaned without making changes}
                           {--id= : Clean specific article ID}';

    /**
     * The console command description.
     */
    protected $description = 'Clean JavaScript and unwanted attributes from article content';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $articleId = $this->option('id');
        
        $this->info('Starting article content cleanup...');
        
        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No changes will be made');
        }
        
        // Get articles to process
        $query = Article::query();
        if ($articleId) {
            $query->where('id', $articleId);
        }
        
        $articles = $query->get();
        
        if ($articles->isEmpty()) {
            $this->error('No articles found to process.');
            return 1;
        }
        
        $this->info("Found {$articles->count()} article(s) to process");
        
        $cleanedCount = 0;
        $errorCount = 0;
        
        foreach ($articles as $article) {
            try {
                $this->processArticle($article, $isDryRun, $cleanedCount);
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("Error processing article {$article->id}: " . $e->getMessage());
            }
        }
        
        $this->newLine();
        
        if ($isDryRun) {
            $this->info("DRY RUN COMPLETE:");
            $this->info("- {$cleanedCount} articles would be cleaned");
            $this->info("- {$errorCount} errors encountered");
            $this->info("Run without --dry-run to apply changes");
        } else {
            $this->info("CLEANUP COMPLETE:");
            $this->info("- {$cleanedCount} articles cleaned");
            $this->info("- {$errorCount} errors encountered");
        }
        
        return 0;
    }
    
    private function processArticle(Article $article, bool $isDryRun, int &$cleanedCount)
    {
        $originalContent = $article->content;
        
        if (empty($originalContent)) {
            return;
        }
        
        // Check if content needs cleaning
        if (!$this->needsCleaning($originalContent)) {
            return;
        }
        
        $this->info("Processing article {$article->id}: {$article->title}");
        
        // Create temporary article instance for sanitization
        $tempArticle = new Article();
        $cleanedContent = $tempArticle->sanitizeContentForSave($originalContent);
        
        // Show changes
        $this->showChanges($originalContent, $cleanedContent, $isDryRun);
        
        if (!$isDryRun) {
            // Save the cleaned content
            $article->content = $cleanedContent;
            $article->save();
            
            $this->line("✅ Article {$article->id} cleaned and saved");
        } else {
            $this->line("📋 Article {$article->id} would be cleaned");
        }
        
        $cleanedCount++;
    }
    
    private function needsCleaning(string $content): bool
    {
        $problematicPatterns = [
            '/function\s*\(/i',
            '/document\./i',
            '/console\./i',
            '/addEventListener/i',
            '/data-click-handler/i',
            '/data-resize-enabled/i',
            '/createSizeToolbar/i',
            '/style\.border/i',
            '/onclick\s*=/i',
            '/javascript:/i'
        ];
        
        foreach ($problematicPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function showChanges(string $original, string $cleaned, bool $isDryRun)
    {
        $originalLength = strlen($original);
        $cleanedLength = strlen($cleaned);
        $reduction = $originalLength - $cleanedLength;
        
        if ($reduction > 0) {
            $this->line("  📉 Content size: {$originalLength} → {$cleanedLength} bytes (-{$reduction})");
            
            // Show some examples of what was removed
            if ($this->output->isVerbose()) {
                $this->showRemovedPatterns($original, $cleaned);
            }
        } else {
            $this->line("  ℹ️  No size change detected (content may have been reformatted)");
        }
    }
    
    private function showRemovedPatterns(string $original, string $cleaned)
    {
        $removedPatterns = [];
        
        $checkPatterns = [
            'JavaScript functions' => '/function\s*\([^)]*\)\s*\{[^}]*\}/i',
            'Event handlers' => '/on\w+\s*=\s*["\'][^"\']*["\']/i',
            'Data attributes' => '/data-click-handler|data-resize-enabled|data-size-modified/i',
            'Console statements' => '/console\.\w+\([^)]*\)/i',
            'DOM manipulation' => '/document\.\w+/i'
        ];
        
        foreach ($checkPatterns as $name => $pattern) {
            $originalMatches = preg_match_all($pattern, $original);
            $cleanedMatches = preg_match_all($pattern, $cleaned);
            
            if ($originalMatches > $cleanedMatches) {
                $removed = $originalMatches - $cleanedMatches;
                $removedPatterns[] = "{$name}: {$removed} removed";
            }
        }
        
        if (!empty($removedPatterns)) {
            $this->line("  🗑️  Removed: " . implode(', ', $removedPatterns));
        }
    }
}
