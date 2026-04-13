<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:import {file : Path to the JSON file containing articles}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import articles from a JSON file into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!File::exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Reading articles from: {$filePath}");

        try {
            $jsonContent = File::get($filePath);
            $articles = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('Invalid JSON format: ' . json_last_error_msg());
                return 1;
            }

            if (!is_array($articles)) {
                $this->error('JSON content should be an array of articles');
                return 1;
            }

            $this->info("Found " . count($articles) . " articles to import");

            DB::beginTransaction();

            try {
                $importedCount = 0;
                $skippedCount = 0;

                foreach ($articles as $articleData) {
                    // Check if article already exists by slug
                    $existingArticle = Article::where('slug', $articleData['slug'])->first();
                    
                    if ($existingArticle) {
                        $this->warn("Article with slug '{$articleData['slug']}' already exists. Skipping...");
                        $skippedCount++;
                        continue;
                    }

                    // Prepare the article data
                    $preparedData = $this->prepareArticleData($articleData);

                    // Create the article
                    Article::create($preparedData);
                    $this->info("Imported: {$articleData['title']}");
                    $importedCount++;
                }

                DB::commit();

                $this->info("Import completed successfully!");
                $this->info("Imported: {$importedCount} articles");
                $this->info("Skipped: {$skippedCount} articles (already exist)");

                return 0;

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Error during import: " . $e->getMessage());
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("Error reading file: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Prepare article data for insertion
     *
     * @param array $articleData
     * @return array
     */
    private function prepareArticleData(array $articleData): array
    {
        // Get only the fillable fields from the Article model
        $fillableFields = (new Article())->getFillable();
        
        $prepared = [];
        
        foreach ($fillableFields as $field) {
            if (isset($articleData[$field])) {
                $prepared[$field] = $articleData[$field];
            }
        }

        // Handle special field transformations if needed
        if (isset($articleData['published_at']) && $articleData['published_at']) {
            $prepared['published_at'] = Carbon::parse($articleData['published_at']);
        }

        if (isset($articleData['created_at']) && $articleData['created_at']) {
            $prepared['created_at'] = Carbon::parse($articleData['created_at']);
        }

        if (isset($articleData['updated_at']) && $articleData['updated_at']) {
            $prepared['updated_at'] = Carbon::parse($articleData['updated_at']);
        }

        // Handle tags - convert string to array if needed
        if (isset($prepared['tags']) && is_string($prepared['tags'])) {
            $prepared['tags'] = explode(',', $prepared['tags']);
            $prepared['tags'] = array_map('trim', $prepared['tags']);
        }

        // Ensure boolean fields are properly cast
        if (isset($prepared['is_published'])) {
            $prepared['is_published'] = (bool) $prepared['is_published'];
        }

        if (isset($prepared['is_featured'])) {
            $prepared['is_featured'] = (bool) $prepared['is_featured'];
        }

        return $prepared;
    }
}