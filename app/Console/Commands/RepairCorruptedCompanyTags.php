<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * strength_tags / safety_items が二重JSONエンコードされて壊れているレコードを復元する。
 *
 * 原因: Filament管理画面等で「strength_tags_for_form」（カンマ区切り文字列）経由の
 * 保存を繰り返した際、setStrengthTagsAttribute() が既にJSON化済みの文字列に対して
 * 再度 explode(',', ...) を行い、壊れた配列を再json_encodeしていた。
 *
 * 壊れたデータは各要素をカンマで再結合すると元の正しいJSON文字列に戻る性質があるため、
 * それを利用して復元する。
 */
class RepairCorruptedCompanyTags extends Command
{
    protected $signature = 'companies:repair-tags {--dry-run : 実際には保存せず対象件数のみ表示}';

    protected $description = 'strength_tags / safety_items の二重JSONエンコード破損を復元する';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $fixed = 0;
        $skipped = 0;

        foreach (Company::all() as $company) {
            foreach (['strength_tags', 'safety_items'] as $field) {
                $arr = $company->{$field};
                if (!is_array($arr) || count($arr) === 0) {
                    continue;
                }

                $looksCorrupted = false;
                foreach ($arr as $el) {
                    if (is_string($el) && (str_contains($el, '"') || str_starts_with($el, '['))) {
                        $looksCorrupted = true;
                        break;
                    }
                }

                if (!$looksCorrupted) {
                    continue;
                }

                $rejoined = implode(',', $arr);
                $decoded = json_decode($rejoined, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $this->line("FIX id={$company->id} field={$field}: " . json_encode($decoded, JSON_UNESCAPED_UNICODE));
                    if (!$dryRun) {
                        DB::table('companies')->where('id', $company->id)->update([
                            $field => json_encode($decoded, JSON_UNESCAPED_UNICODE),
                        ]);
                    }
                    $fixed++;
                } else {
                    $this->warn("SKIP（復元できず）id={$company->id} field={$field}: {$rejoined}");
                    $skipped++;
                }
            }
        }

        $this->info(($dryRun ? '[DRY RUN] ' : '') . "修復対象: {$fixed}件 / 復元不可: {$skipped}件");

        return self::SUCCESS;
    }
}
