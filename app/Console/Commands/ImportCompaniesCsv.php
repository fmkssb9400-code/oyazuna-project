<?php

namespace App\Console\Commands;

use App\Services\CompanyCsvImporter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportCompaniesCsv extends Command
{
    protected $signature = 'companies:import-csv {--dry-run : 実際の保存を行わず、集計のみ表示}';

    protected $description = 'CSVファイルからCompanyデータを一括インポートします';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $csvPath = storage_path('app/import/companies.csv');

        if ($dryRun) {
            $this->info('🔍 ドライラン モードで実行します（実際の保存は行いません）');
        }

        $this->info('📁 CSVファイルを確認中...');

        if (!file_exists($csvPath)) {
            $this->error("❌ CSVファイルが見つかりません: {$csvPath}");
            return Command::FAILURE;
        }

        $this->info("✅ CSVファイルを発見: {$csvPath}");
        $this->info('🔄 インポートを開始します...');

        try {
            $importer = new CompanyCsvImporter($dryRun);
            $stats = $importer->importFromCsv($csvPath);

            $this->displayResults($stats, $dryRun);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ インポート中にエラーが発生しました: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function displayResults(array $stats, bool $dryRun): void
    {
        $this->info('');
        $this->info('=== インポート結果 ===');
        
        if ($dryRun) {
            $this->info('📊 ドライラン結果:');
        } else {
            $this->info('📊 実行結果:');
        }

        $this->info("✅ 追加: {$stats['added']}件");
        $this->info("⏭️  スキップ: {$stats['skipped']}件 (重複)");
        $this->info("❌ 失敗: {$stats['failed']}件");

        if (!empty($stats['errors'])) {
            $this->info('');
            $this->error('🔍 エラー詳細:');
            foreach ($stats['errors'] as $error) {
                $this->error("   • {$error}");
            }
        }

        $total = $stats['added'] + $stats['skipped'] + $stats['failed'];
        $this->info('');
        $this->info("📈 処理総数: {$total}件");

        if ($dryRun && $stats['added'] > 0) {
            $this->info('');
            $this->comment('💡 実際にインポートするには --dry-run オプションを外して再実行してください。');
        }

        if (!$dryRun && $stats['added'] > 0) {
            $this->info('');
            $this->info('🎉 インポートが正常に完了しました！');
        }
    }
}