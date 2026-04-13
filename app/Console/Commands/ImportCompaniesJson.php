<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Prefecture;
use App\Models\ServiceCategory;
use App\Models\ServiceMethod;
use App\Models\BuildingType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportCompaniesJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:import-json {file : Path to the JSON file containing companies} {--update : Update existing companies instead of skipping}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import companies from a JSON file into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        $shouldUpdate = $this->option('update');

        if (!File::exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Reading companies from: {$filePath}");

        try {
            $jsonContent = File::get($filePath);
            $companies = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('Invalid JSON format: ' . json_last_error_msg());
                return 1;
            }

            if (!is_array($companies)) {
                $this->error('JSON content should be an array of companies');
                return 1;
            }

            $this->info("Found " . count($companies) . " companies to import");

            DB::beginTransaction();

            try {
                $importedCount = 0;
                $updatedCount = 0;
                $skippedCount = 0;

                foreach ($companies as $companyData) {
                    $result = $this->importCompany($companyData, $shouldUpdate);
                    
                    if ($result === 'imported') {
                        $importedCount++;
                    } elseif ($result === 'updated') {
                        $updatedCount++;
                    } else {
                        $skippedCount++;
                    }
                }

                DB::commit();

                $this->info("Import completed successfully!");
                $this->info("Imported: {$importedCount} companies");
                $this->info("Updated: {$updatedCount} companies");
                $this->info("Skipped: {$skippedCount} companies (already exist)");

                return 0;

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Error during import: " . $e->getMessage());
                $this->error("Stack trace: " . $e->getTraceAsString());
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("Error reading file: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Import a single company
     *
     * @param array $companyData
     * @param bool $shouldUpdate
     * @return string
     */
    private function importCompany(array $companyData, bool $shouldUpdate): string
    {
        // Check if company already exists by id or slug
        $existingCompany = Company::where('id', $companyData['id'] ?? null)
                                 ->orWhere('slug', $companyData['slug'] ?? null)
                                 ->first();
        
        if ($existingCompany && !$shouldUpdate) {
            $this->warn("Company with slug '{$companyData['slug']}' already exists. Skipping...");
            return 'skipped';
        }

        // Prepare the company data
        $preparedData = $this->prepareCompanyData($companyData);

        if ($existingCompany && $shouldUpdate) {
            // Update existing company
            $existingCompany->update($preparedData);
            $company = $existingCompany;
            $this->info("Updated: {$companyData['name']}");
            $action = 'updated';
        } else {
            // Create new company with specific ID
            $company = new Company();
            $company->id = $companyData['id'];
            $company->fill($preparedData);
            $company->save();
            $this->info("Imported: {$companyData['name']}");
            $action = 'imported';
        }

        // Handle relationships
        $this->attachRelationships($company, $companyData);

        return $action;
    }

    /**
     * Prepare company data for insertion
     *
     * @param array $companyData
     * @return array
     */
    private function prepareCompanyData(array $companyData): array
    {
        // Get only the fillable fields from the Company model
        $fillableFields = (new Company())->getFillable();
        
        $prepared = [];
        
        foreach ($fillableFields as $field) {
            if (isset($companyData[$field])) {
                $prepared[$field] = $companyData[$field];
            }
        }

        // Handle special field transformations
        if (isset($companyData['published_at']) && $companyData['published_at']) {
            $prepared['published_at'] = Carbon::parse($companyData['published_at']);
        }

        if (isset($companyData['created_at']) && $companyData['created_at']) {
            $prepared['created_at'] = Carbon::parse($companyData['created_at']);
        }

        if (isset($companyData['updated_at']) && $companyData['updated_at']) {
            $prepared['updated_at'] = Carbon::parse($companyData['updated_at']);
        }

        // Handle boolean fields
        $booleanFields = [
            'rope_support', 'gondola_supported', 'branco_supported', 
            'aerial_platform_supported', 'is_featured', 'emergency_supported', 'insurance'
        ];
        
        foreach ($booleanFields as $field) {
            if (isset($prepared[$field])) {
                $prepared[$field] = (bool) $prepared[$field];
            }
        }

        // Handle array fields - ensure they are properly formatted
        $arrayFields = [
            'areas', 'safety_items', 'security_points', 'strength_tags', 
            'service_categories', 'tags', 'area_regions'
        ];
        
        foreach ($arrayFields as $field) {
            if (isset($prepared[$field])) {
                if (is_string($prepared[$field])) {
                    // If it's a JSON string, decode it
                    $decoded = json_decode($prepared[$field], true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $prepared[$field] = $decoded;
                    } else {
                        // If not JSON, treat as comma-separated
                        $prepared[$field] = array_filter(array_map('trim', explode(',', $prepared[$field])));
                    }
                } elseif (!is_array($prepared[$field])) {
                    $prepared[$field] = [];
                }
            }
        }

        // Handle numeric fields
        $numericFields = [
            'sort_order', 'recommend_score', 'safety_score', 'performance_score', 
            'review_count', 'max_floor', 'rank_score'
        ];
        
        foreach ($numericFields as $field) {
            if (isset($prepared[$field])) {
                $prepared[$field] = is_numeric($prepared[$field]) ? (int) $prepared[$field] : null;
            }
        }

        // Handle decimal field
        if (isset($prepared['review_score'])) {
            $prepared['review_score'] = is_numeric($prepared['review_score']) ? (float) $prepared['review_score'] : 0.00;
        }

        return $prepared;
    }

    /**
     * Attach relationships to the company
     *
     * @param Company $company
     * @param array $companyData
     */
    private function attachRelationships(Company $company, array $companyData): void
    {
        // Clear existing relationships
        $company->prefectures()->detach();
        $company->serviceCategories()->detach();
        $company->serviceMethods()->detach();
        $company->buildingTypes()->detach();

        // Attach prefectures based on areas
        if (isset($companyData['areas']) && is_array($companyData['areas'])) {
            $prefectureIds = Prefecture::whereIn('name', $companyData['areas'])->pluck('id')->toArray();
            if (!empty($prefectureIds)) {
                $company->prefectures()->attach($prefectureIds);
            }
        }

        // Attach service categories
        if (isset($companyData['service_categories']) && is_array($companyData['service_categories'])) {
            $serviceCategoryIds = ServiceCategory::whereIn('key', $companyData['service_categories'])->pluck('id')->toArray();
            if (!empty($serviceCategoryIds)) {
                $company->serviceCategories()->attach($serviceCategoryIds);
            }
        }

        // Note: ServiceMethod and BuildingType relationships would need additional data in the JSON
        // For now, we'll skip these unless the data is available
    }
}