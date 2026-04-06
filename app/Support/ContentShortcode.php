<?php

namespace App\Support;

class ContentShortcode
{
    public static function render($content)
    {
        // CTAトークンを検索して置換
        $ctaPattern = '/\[\[CTA\|(blue|orange)\|([^|]+)\|([^\]]+)\]\]/';
        $content = preg_replace_callback($ctaPattern, function ($matches) {
            $color = $matches[1];
            $url = htmlspecialchars($matches[2]);
            $text = htmlspecialchars($matches[3]);
            
            $colorClass = $color === 'orange' 
                ? 'bg-orange-600 hover:bg-orange-700' 
                : 'bg-blue-600 hover:bg-blue-700';
            
            return '<div class="my-8">
  <a href="' . $url . '" target="_blank" rel="noopener"
     class="flex items-center justify-center gap-2 ' . $colorClass . ' text-white px-8 py-4 rounded-2xl font-bold text-lg hover:shadow-lg transition-all duration-200 transform hover:scale-105 w-full max-w-3xl">
    ' . $text . '
  </a>
</div>';
        }, $content);

        // データ付きテーブルトークンを検索して置換
        $dataTablePattern = '/\[\[TABLE\|cols=(\d+)\|rows=(\d+)\|data=([A-Za-z0-9+\/=]+)\]\]/';
        $content = preg_replace_callback($dataTablePattern, function ($matches) {
            $cols = max(2, min(6, intval($matches[1])));
            $rows = max(2, min(20, intval($matches[2])));
            $encodedData = $matches[3];
            
            return self::generateTableWithData($cols, $rows, $encodedData);
        }, $content);

        // 従来のテーブルトークンも引き続き対応（後方互換）
        $tablePattern = '/\[\[TABLE\|cols=(\d+)\|rows=(\d+)\]\]/';
        $content = preg_replace_callback($tablePattern, function ($matches) {
            $cols = max(2, min(6, intval($matches[1])));
            $rows = max(2, min(20, intval($matches[2])));
            
            return self::generateTable($cols, $rows);
        }, $content);

        // テキスト形式のテーブル変換を無効化
        // HTMLテーブルはそのまま表示するため、テキスト変換処理は行わない
        // $content = self::convertTextTablesToHTML($content);

        return $content;
    }

    private static function generateTable($cols, $rows)
    {
        $html = '<div class="my-8 overflow-x-auto">
  <table class="w-full min-w-[640px] border border-gray-200 text-sm">
    <thead>
      <tr class="bg-gray-100">';
        
        // ヘッダー行を生成
        $html .= '<th class="border border-gray-200 px-4 py-3 text-left font-bold">項目</th>';
        for ($i = 1; $i < $cols; $i++) {
            $html .= '<th class="border border-gray-200 px-4 py-3 text-left font-bold">比較' . $i . '</th>';
        }
        
        $html .= '</tr>
    </thead>
    <tbody>';
        
        // データ行を生成
        for ($row = 1; $row < $rows; $row++) {
            $html .= '<tr>';
            
            // 項目列（1列目）
            $html .= '<th class="border border-gray-200 px-4 py-3 text-left bg-gray-50 font-bold">項目' . $row . '</th>';
            
            // データ列
            for ($col = 1; $col < $cols; $col++) {
                $html .= '<td class="border border-gray-200 px-4 py-3">-</td>';
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody>
  </table>
</div>';
        
        return $html;
    }

    private static function generateTableWithData($cols, $rows, $encodedData)
    {
        try {
            // Base64デコード → JSON decode
            $jsonData = base64_decode($encodedData);
            $tableData = json_decode($jsonData, true);
            
            if (!$tableData || !isset($tableData['headers']) || !isset($tableData['rows'])) {
                // データが壊れている場合はフォールバック
                return self::generateTable($cols, $rows);
            }
            
            $headers = $tableData['headers'];
            $dataRows = $tableData['rows'];
            
        } catch (Exception $e) {
            // エラーの場合はフォールバック
            return self::generateTable($cols, $rows);
        }
        
        $html = '<div class="my-8 overflow-x-auto">
  <table class="w-full min-w-[640px] border border-gray-200 text-sm">
    <thead>
      <tr class="bg-gray-100">';
        
        // ヘッダー行を生成（実際のデータを使用）
        foreach ($headers as $header) {
            $html .= '<th class="border border-gray-200 px-4 py-3 text-left font-bold">' . htmlspecialchars($header) . '</th>';
        }
        
        $html .= '</tr>
    </thead>
    <tbody>';
        
        // データ行を生成（実際のデータを使用）
        foreach ($dataRows as $rowIndex => $rowData) {
            $html .= '<tr>';
            
            foreach ($rowData as $colIndex => $cellData) {
                if ($colIndex === 0) {
                    // 1列目は項目列
                    $html .= '<th class="border border-gray-200 px-4 py-3 text-left bg-gray-50 font-bold">' . htmlspecialchars($cellData) . '</th>';
                } else {
                    // その他は通常のセル
                    $html .= '<td class="border border-gray-200 px-4 py-3">' . htmlspecialchars($cellData) . '</td>';
                }
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody>
  </table>
</div>';
        
        return $html;
    }

    private static function convertTextTablesToHTML($content)
    {
        // パターン1: 新しい編集可能テーブル形式 (ヘッダー1ヘッダー2...)
        if (strpos($content, 'ヘッダー') !== false && strpos($content, '行') !== false) {
            $content = preg_replace_callback(
                '/<strong>ヘッダー\d+[^<]*<\/strong>[^<]*(?:<br><strong>行\d+<\/strong>[^<]*)+/i',
                function($matches) {
                    return self::convertNewTableFormat($matches[0]);
                },
                $content
            );
        }
        
        // パターン2: 従来の項目比較形式
        if (strpos($content, '項目比較') !== false && strpos($content, '項目1 |') !== false) {
            
            // 特定のパターンを直接置換
            $patterns = [
                // 3列のテーブル (項目比較1比較2)
                '/(<strong>項目比較1比較2<\/strong>項目1\s*\|\s*[^<]*<br>項目2\s*\|\s*[^<]*<br>項目3\s*\|\s*[^<]*<br>項目4\s*\|\s*[^<]*)/i' => function($matches) {
                    return self::generateSpecificTable($matches[0], 3);
                },
                
                // 2列のテーブル (項目比較1)
                '/(<strong>項目比較1<\/strong>項目1\s*\|\s*[^<]*<br>項目2\s*\|\s*[^<]*<br>項目3\s*\|\s*[^<]*<br>項目4\s*\|\s*[^<]*)/i' => function($matches) {
                    return self::generateSpecificTable($matches[0], 2);
                }
            ];
            
            foreach ($patterns as $pattern => $replacement) {
                $content = preg_replace_callback($pattern, $replacement, $content);
            }
        }
        
        return $content;
    }
    
    private static function generateSpecificTable($textContent, $cols)
    {
        $headers = ['項目'];
        for ($i = 1; $i < $cols; $i++) {
            $headers[] = '比較' . $i;
        }
        
        // データ行を抽出
        preg_match_all('/項目(\d+)\s*\|\s*([^<]+)(?=<br>|$)/i', $textContent, $matches, PREG_SET_ORDER);
        
        $html = '<div class="my-8 overflow-x-auto">
  <table class="w-full min-w-[640px] border border-gray-200 text-sm">
    <thead>
      <tr class="bg-gray-100">';
        
        foreach ($headers as $header) {
            $html .= '<th class="border border-gray-200 px-4 py-3 text-left font-bold">' . htmlspecialchars($header) . '</th>';
        }
        
        $html .= '</tr>
    </thead>
    <tbody>';
        
        foreach ($matches as $match) {
            $html .= '<tr>';
            $html .= '<th class="border border-gray-200 px-4 py-3 text-left bg-gray-50 font-bold">項目' . $match[1] . '</th>';
            
            $cellData = array_map('trim', explode('|', $match[2]));
            foreach ($cellData as $cell) {
                $html .= '<td class="border border-gray-200 px-4 py-3">' . htmlspecialchars($cell) . '</td>';
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody>
  </table>
</div>';
        
        return $html;
    }
    
    private static function convertNewTableFormat($textContent)
    {
        try {
            // 具体的な形式「ヘッダー1ヘッダー2ヘッダー3行1 | データ | データ」を解析
            
            // 最初の行からヘッダーとデータを分離
            preg_match('/^<strong>([^<]*)<\/strong>\s*\|\s*([^<]+)/', $textContent, $firstRowMatch);
            if (!$firstRowMatch) return $textContent;
            
            // ヘッダー部分を解析
            $headerPart = $firstRowMatch[1];
            $firstDataPart = $firstRowMatch[2];
            
            // ヘッダーを分離（ヘッダー1ヘッダー2ヘッダー3行1 → [ヘッダー1, ヘッダー2, ヘッダー3] + 行1）
            preg_match('/^((?:ヘッダー\d+)+)(.+)$/', $headerPart, $headerMatch);
            if (!$headerMatch) return $textContent;
            
            // ヘッダーを個別に抽出
            preg_match_all('/ヘッダー\d+/', $headerMatch[1], $headers);
            $headers = $headers[0];
            
            // 最初のデータ行の項目名
            $firstRowName = trim($headerMatch[2]);
            
            // 最初のデータ行のデータ部分
            $firstRowData = array_map('trim', explode('|', $firstDataPart));
            
            // 残りの行を処理
            $rows = [[$firstRowName, ...$firstRowData]];
            
            // 他の行を抽出
            preg_match_all('/<strong>(行\d+)<\/strong>\s*\|\s*([^<]+)/i', $textContent, $otherRows, PREG_SET_ORDER);
            
            foreach ($otherRows as $match) {
                $rowName = $match[1];
                $rowData = array_map('trim', explode('|', $match[2]));
                $rows[] = [$rowName, ...$rowData];
            }
            
            if (empty($headers) || empty($rows)) return $textContent;
            
            // HTMLテーブルを生成
            $html = '<div class="my-8 overflow-x-auto">
  <table class="w-full min-w-[640px] border border-gray-200 text-sm">
    <thead>
      <tr class="bg-gray-100">';
            
            // ヘッダー行：最初の列は空、その後にヘッダー
            $html .= '<th class="border border-gray-200 px-4 py-3 text-left font-bold">項目</th>';
            foreach ($headers as $header) {
                $html .= '<th class="border border-gray-200 px-4 py-3 text-left font-bold">' . htmlspecialchars($header) . '</th>';
            }
            
            $html .= '</tr>
    </thead>
    <tbody>';
            
            foreach ($rows as $rowData) {
                $html .= '<tr>';
                
                foreach ($rowData as $colIndex => $cellData) {
                    if ($colIndex === 0) {
                        // 1列目は項目列
                        $html .= '<th class="border border-gray-200 px-4 py-3 text-left bg-gray-50 font-bold">' . htmlspecialchars($cellData) . '</th>';
                    } else {
                        // その他は通常のセル
                        $html .= '<td class="border border-gray-200 px-4 py-3">' . htmlspecialchars($cellData) . '</td>';
                    }
                }
                
                $html .= '</tr>';
            }
            
            $html .= '</tbody>
  </table>
</div>';
            
            return $html;
            
        } catch (Exception $e) {
            // エラーの場合は元のテキストを返す
            return $textContent;
        }
    }
}