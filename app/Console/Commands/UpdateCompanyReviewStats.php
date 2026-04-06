<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Review;

class UpdateCompanyReviewStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reviews:update-stats {--company-id= : Update specific company only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update company review statistics (count and average rating)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companyId = $this->option('company-id');
        
        if ($companyId) {
            $company = Company::find($companyId);
            if (!$company) {
                $this->error("Company with ID {$companyId} not found.");
                return 1;
            }
            $this->updateCompanyStats($company);
            $this->info("Updated stats for company: {$company->name}");
        } else {
            $companies = Company::all();
            $bar = $this->output->createProgressBar($companies->count());
            $bar->start();
            
            foreach ($companies as $company) {
                $this->updateCompanyStats($company);
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine();
            $this->info('All company review statistics updated successfully!');
        }
        
        return 0;
    }
    
    private function updateCompanyStats(Company $company)
    {
        $publishedReviews = $company->reviews()->published();
        
        // Count published reviews
        $reviewsCount = $publishedReviews->count();
        
        // Average rating from total_score
        $averageRating = $publishedReviews->avg('total_score') ?: 0;
        
        // Update the company record if using stored stats approach
        // (Currently we use real-time calculation, so this is optional)
        $company->update([
            'review_count' => $reviewsCount,
            'review_score' => $averageRating,
        ]);
        
        $this->line("Company: {$company->name} - Reviews: {$reviewsCount}, Avg: " . number_format($averageRating, 2));
    }
}
