<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CompanyCsvImporter
{
    private array $stats = [
        'added' => 0,
        'skipped' => 0,
        'failed' => 0,
        'errors' => []
    ];

    private bool $dryRun = false;

    public function __construct(bool $dryRun = false)
    {
        $this->dryRun = $dryRun;
    }

    public function importFromCsv(string $csvPath): array
    {
        if (!file_exists($csvPath)) {
            throw new \Exception("CSVファイルが見つかりません: {$csvPath}");
        }

        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            throw new \Exception("CSVファイルを開けません: {$csvPath}");
        }

        $lineNumber = 0;
        $headers = null;
        
        while (($data = fgetcsv($handle)) !== false) {
            $lineNumber++;
            
            // Skip empty lines
            if (empty(array_filter($data))) {
                continue;
            }
            
            // Find header line (contains "会社名")
            if ($headers === null) {
                if (in_array('会社名', $data)) {
                    $headers = $data;
                    continue;
                }
                continue; // Skip until we find headers
            }

            try {
                $this->processRow($data, $headers, $lineNumber);
            } catch (\Exception $e) {
                $this->stats['failed']++;
                $this->stats['errors'][] = "行 {$lineNumber}: " . $e->getMessage();
                Log::error("CSV import error at line {$lineNumber}: " . $e->getMessage());
            }
        }

        fclose($handle);
        return $this->getStats();
    }

    private function processRow(array $data, array $headers, int $lineNumber): void
    {
        // Create associative array from headers and data
        $row = array_combine($headers, $data);
        if ($row === false) {
            throw new \Exception("ヘッダーとデータの数が一致しません");
        }

        // Extract required fields
        $name = trim($row['会社名'] ?? '');
        $website_url = trim($row['公式サイトURL'] ?? '');

        if (empty($name)) {
            throw new \Exception("会社名が空です");
        }

        // Check for duplicates
        if ($this->isDuplicate($name, $website_url)) {
            $this->stats['skipped']++;
            return;
        }

        // Prepare company data
        $companyData = $this->prepareCompanyData($row);

        // Create company if not dry run
        if (!$this->dryRun) {
            Company::create($companyData);
        }

        $this->stats['added']++;
    }

    private function isDuplicate(string $name, string $websiteUrl): bool
    {
        // Check by website URL if provided
        if (!empty($websiteUrl)) {
            $normalizedUrl = $this->normalizeUrl($websiteUrl);
            
            // Get all companies and check URLs manually for SQLite compatibility
            $existingCompanies = Company::whereNotNull('website_url')->get();
            foreach ($existingCompanies as $company) {
                if ($this->normalizeUrl($company->website_url) === $normalizedUrl) {
                    return true;
                }
            }
        }

        // Check by normalized company name
        $normalizedName = $this->normalizeCompanyName($name);
        $existingCompanies = Company::all();
        
        foreach ($existingCompanies as $company) {
            if ($this->normalizeCompanyName($company->name) === $normalizedName) {
                return true;
            }
        }

        return false;
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);
        $url = strtolower($url);
        
        // Remove protocol differences
        $url = preg_replace('/^https?:\/\//', '', $url);
        
        // Remove trailing slash
        $url = rtrim($url, '/');
        
        return $url;
    }

    private function normalizeCompanyName(string $name): string
    {
        $name = trim($name);
        
        // Convert full-width characters to half-width
        $name = mb_convert_kana($name, 'as', 'UTF-8');
        
        // Remove spaces
        $name = preg_replace('/\s+/', '', $name);
        
        // Remove company suffixes
        $suffixes = [
            '株式会社', 'かぶしきがいしゃ', 'カブシキガイシャ',
            '有限会社', 'ゆうげんがいしゃ', 'ユウゲンガイシャ',
            '(株)', '（株）', '(有)', '（有）',
            'Inc.', 'inc.', 'Co.', 'co.', 'Ltd.', 'ltd.',
            'Corporation', 'Corp.', 'corp.'
        ];
        
        foreach ($suffixes as $suffix) {
            $name = str_ireplace($suffix, '', $name);
        }
        
        return strtolower($name);
    }

    private function prepareCompanyData(array $row): array
    {
        // Ensure proper encoding for text fields
        $name = mb_convert_encoding(trim($row['会社名'] ?? ''), 'UTF-8', 'auto');
        $description = mb_convert_encoding(trim($row['会社紹介（短く）'] ?? ''), 'UTF-8', 'auto');
        $websiteUrl = trim($row['公式サイトURL'] ?? '');

        $data = [
            'name' => $name,
            'slug' => $this->generateUniqueSlug(),
            'description' => $description,
            'website_url' => $websiteUrl,
            'official_url' => $websiteUrl, // 公式サイトボタン用にofficial_urlにも設定
            'areas' => $this->normalizePrefectures($row['対応エリア'] ?? ''),
            'rope_support' => $this->parseBoolean($row['ロープ対応'] ?? ''),
            'safety_items' => $this->parseArrayField($row['安全情報'] ?? ''),
            'emergency_supported' => $this->parseBoolean($row['即日・緊急対応'] ?? ''),
            'strength_tags' => $this->parseArrayField($row['強みタグ'] ?? ''),
        ];

        // Add optional fields if they exist
        if (isset($row['最大対応階数']) && !empty(trim($row['最大対応階数']))) {
            $data['max_floor'] = (int) trim($row['最大対応階数']);
        }

        if (isset($row['実績要約']) && !empty(trim($row['実績要約']))) {
            $data['achievements_summary'] = mb_convert_encoding(trim($row['実績要約']), 'UTF-8', 'auto');
        }

        if (isset($row['ゴンドラ対応'])) {
            $data['gondola_supported'] = $this->parseBoolean($row['ゴンドラ対応']);
        }

        // Set default values for required fields not in CSV
        $data['published_at'] = now();
        $data['email_quote'] = $websiteUrl ?: 'contact@' . str_replace([' ', '　'], '', $name) . '.example.com';

        return $data;
    }

    private function generateUniqueSlug(): string
    {
        // Get all existing slugs and find the highest numeric one
        $allSlugs = Company::pluck('slug')->toArray();
        $numericSlugs = [];
        
        foreach ($allSlugs as $slug) {
            if (is_numeric($slug)) {
                $numericSlugs[] = (int) $slug;
            }
        }
        
        $nextNumber = empty($numericSlugs) ? 1 : max($numericSlugs) + 1;

        // Ensure uniqueness
        while (Company::where('slug', (string) $nextNumber)->exists()) {
            $nextNumber++;
        }

        return (string) $nextNumber;
    }

    private function normalizePrefectures(string $prefecturesText): array
    {
        if (empty(trim($prefecturesText))) {
            return [];
        }

        // Ensure proper UTF-8 encoding
        $prefecturesText = mb_convert_encoding($prefecturesText, 'UTF-8', 'auto');

        // Split by common delimiters
        $areas = preg_split('/[・、，,\s]+/', $prefecturesText);
        $normalizedAreas = [];

        foreach ($areas as $area) {
            $area = trim($area);
            if (empty($area)) {
                continue;
            }

            // Ensure UTF-8 encoding for each area
            $area = mb_convert_encoding($area, 'UTF-8', 'auto');

            // Remove parentheses content like （大田原市）
            $area = preg_replace('/[（(][^）)]*[）)]/', '', $area);
            $area = trim($area);

            if (empty($area)) {
                continue;
            }

            // Normalize prefecture names
            $normalizedArea = $this->normalizePrefectureName($area);
            if (!in_array($normalizedArea, $normalizedAreas)) {
                $normalizedAreas[] = $normalizedArea;
            }
        }

        return $normalizedAreas;
    }

    private function normalizePrefectureName(string $area): string
    {
        $area = trim($area);

        // Prefecture mapping for completion
        $prefectureMap = [
            '北海道' => '北海道',
            '青森' => '青森県',
            '岩手' => '岩手県',
            '宮城' => '宮城県',
            '秋田' => '秋田県',
            '山形' => '山形県',
            '福島' => '福島県',
            '茨城' => '茨城県',
            '栃木' => '栃木県',
            '群馬' => '群馬県',
            '埼玉' => '埼玉県',
            '千葉' => '千葉県',
            '東京' => '東京都',
            '神奈川' => '神奈川県',
            '新潟' => '新潟県',
            '富山' => '富山県',
            '石川' => '石川県',
            '福井' => '福井県',
            '山梨' => '山梨県',
            '長野' => '長野県',
            '岐阜' => '岐阜県',
            '静岡' => '静岡県',
            '愛知' => '愛知県',
            '三重' => '三重県',
            '滋賀' => '滋賀県',
            '京都' => '京都府',
            '大阪' => '大阪府',
            '兵庫' => '兵庫県',
            '奈良' => '奈良県',
            '和歌山' => '和歌山県',
            '鳥取' => '鳥取県',
            '島根' => '島根県',
            '岡山' => '岡山県',
            '広島' => '広島県',
            '山口' => '山口県',
            '徳島' => '徳島県',
            '香川' => '香川県',
            '愛媛' => '愛媛県',
            '高知' => '高知県',
            '福岡' => '福岡県',
            '佐賀' => '佐賀県',
            '長崎' => '長崎県',
            '熊本' => '熊本県',
            '大分' => '大分県',
            '宮崎' => '宮崎県',
            '鹿児島' => '鹿児島県',
            '沖縄' => '沖縄県'
        ];

        // If already has proper suffix, return as is
        if (preg_match('/[都道府県]$/', $area)) {
            return $area;
        }

        // Try to find in map
        if (isset($prefectureMap[$area])) {
            return $prefectureMap[$area];
        }

        // If not found, return as is (might be a special case like 全国対応)
        return $area;
    }

    private function parseBoolean(string $value): bool
    {
        $value = trim(strtolower($value));
        
        $trueValues = ['あり', 'true', '1', 'yes', 'はい', '○', '可', '対応'];
        
        foreach ($trueValues as $trueValue) {
            if (strpos($value, $trueValue) !== false) {
                return true;
            }
        }
        
        return false;
    }

    private function parseArrayField(string $value): array
    {
        if (empty(trim($value))) {
            return [];
        }

        // Ensure proper UTF-8 encoding
        $value = mb_convert_encoding($value, 'UTF-8', 'auto');

        // Split by semicolon or newline
        $items = preg_split('/[;\n\r]+/', $value);
        $result = [];

        foreach ($items as $item) {
            $item = trim($item);
            if (!empty($item)) {
                // Ensure UTF-8 encoding for each item
                $item = mb_convert_encoding($item, 'UTF-8', 'auto');
                $result[] = $item;
            }
        }

        return $result;
    }

    public function getStats(): array
    {
        return $this->stats;
    }
}