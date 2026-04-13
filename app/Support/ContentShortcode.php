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

        // カスタムHTMLショートコードを処理
        $content = self::processHtmlShortcodes($content);

        // 過剰な改行を削除
        $content = self::cleanupExcessiveLineBreaks($content);

        // 画像URLを適切なStorage URLに変換
        $content = self::convertImageUrls($content);

        // テキスト形式のテーブル変換を無効化
        // HTMLテーブルはそのまま表示するため、テキスト変換処理は行わない
        // $content = self::convertTextTablesToHTML($content);

        return $content;
    }

    private static function cleanupExcessiveLineBreaks($content)
    {
        if (empty($content)) {
            return $content;
        }

        // 生の改行文字（\n）を実際の改行に変換
        $content = str_replace('\\n', "\n", $content);
        
        // 過剰な改行を削除（3つ以上連続する改行を2つに制限）
        $content = preg_replace('/\n{3,}/', "\n\n", $content);
        
        // HTMLタグの後の不要な改行を削除
        $content = preg_replace('/>\s*\n\s*\n\s*</', ">\n<", $content);
        
        // 段落の終わりの過剰な改行を削除
        $content = preg_replace('/\n{2,}\s*$/', "\n", $content);
        $content = preg_replace('/^\s*\n{2,}/', "\n", $content);

        return trim($content);
    }

    public static function convertImageUrls($content)
    {
        if (empty($content)) {
            return $content;
        }

        // /storage/ で始まる画像URLを適切なStorage URLに変換（エスケープされた引用符にも対応）
        $content = preg_replace_callback(
            '/(src=["\\\'])(\/storage\/[^"\\\']+)(["\\\'])/i',
            function ($matches) {
                $originalPath = $matches[2]; // /storage/articles/filename.png
                
                // 既に完全URLの場合はそのまま返す
                if (strpos($originalPath, 'http') === 0) {
                    return $matches[0];
                }
                
                // /storage/ を除去してファイルパスを取得
                $filePath = str_replace('/storage/', '', $originalPath); // articles/filename.png
                
                // Storage::url() を使用して適切なURLを生成
                $storageUrl = \Storage::disk('public')->url($filePath);
                
                return $matches[1] . $storageUrl . $matches[3];
            },
            $content
        );
        
        // Only remove width/height attributes if they don't appear to be intentionally set by admin
        // We'll preserve existing width/height attributes to respect admin image sizing choices
        // Note: This change preserves admin-set image dimensions while still making images responsive
        
        // 画像にレスポンシブなCSSクラスを追加（既存のclassやstyleがある場合も考慮）
        $content = preg_replace_callback(
            '/(<img[^>]*?)(\s+class=["\']([^"\']*)["\'])?([^>]*>)/i',
            function ($matches) {
                $imgStart = $matches[1];
                $existingClasses = isset($matches[3]) ? $matches[3] : '';
                $imgEnd = $matches[4];
                
                // Check if image already has width/height attributes or inline styles
                $hasWidthHeight = (strpos($imgStart . $imgEnd, 'width=') !== false || strpos($imgStart . $imgEnd, 'height=') !== false);
                $hasInlineStyles = (strpos($imgStart . $imgEnd, 'style=') !== false);
                
                // Only add basic responsive classes if no explicit sizing is set
                if (!$hasWidthHeight && !$hasInlineStyles) {
                    $responsiveClasses = 'article-image max-w-full h-auto mx-auto my-4 rounded-lg';
                } else {
                    // If admin has set specific dimensions, just add minimal styling
                    $responsiveClasses = 'article-image mx-auto my-4 rounded-lg';
                }
                
                if ($existingClasses) {
                    $newClasses = $existingClasses . ' ' . $responsiveClasses;
                } else {
                    $newClasses = $responsiveClasses;
                }
                
                return $imgStart . ' class="' . $newClasses . '"' . $imgEnd;
            },
            $content
        );

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

    private static function processHtmlShortcodes($content)
    {
        // [html:harness] ショートコードを処理
        $content = str_replace('[html:harness]', self::getHarnessHtml(), $content);
        
        // Static page specific HTML blocks
        $content = str_replace('[html:jyuyou]', self::getJyuyouHtml(), $content);
        $content = str_replace('[html:gokai]', self::getGokaiHtml(), $content);
        $content = str_replace('[html:advice]', self::getAdviceHtml(), $content);
        $content = str_replace('[html:ninku]', self::getNinkuHtml(), $content);
        $content = str_replace('[html:keisan]', self::getKeisanHtml(), $content);
        $content = str_replace('[html:gondora]', self::getGondoraHtml(), $content);
        $content = str_replace('[html:tuika]', self::getTuikaHtml(), $content);
        
        // その他の未処理のHTMLショートコードを削除（大量の改行を避けるため）
        $content = preg_replace('/\[html:[^\]]+\]/', '', $content);
        
        return $content;
    }

    private static function getHarnessHtml()
    {
        return '
<div class="my-8 p-6 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
    <div class="flex items-start space-x-4">
        <div class="flex-shrink-0">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <h4 class="text-lg font-semibold text-blue-900 mb-2">フルハーネス着用について</h4>
            <p class="text-blue-800 text-sm leading-relaxed mb-4">
                高所ロープ作業において、フルハーネス型安全帯の着用は法的に義務付けられています（労働安全衛生法第539条の2）。
                作業者の安全を確保するため、適切なハーネスの選択と正しい装着方法の確認が重要です。
            </p>
            <div class="bg-white p-4 rounded border border-blue-200">
                <h5 class="font-medium text-blue-900 mb-2">確認ポイント</h5>
                <ul class="text-sm text-blue-800 space-y-1">
                    <li>• JIS規格適合品の使用</li>
                    <li>• 定期的な点検・交換の実施</li>
                    <li>• 作業内容に応じた適切な選択</li>
                    <li>• 正しい装着方法の教育</li>
                </ul>
            </div>
        </div>
    </div>
</div>';
    }
    
    private static function getJyuyouHtml()
    {
        return '<div class="targetlist_23" style="position: relative; margin: 2em 0; padding: 10px 20px 12px 20px; background: #fbf8e5; border: 2px solid #f1e4ab;">
  <span style="position: absolute; top: -15px; left: 15px; background: #f1e4ab; padding: 0 20px; border-radius: 15px; font-weight: bold; box-shadow: 1px 1px 2px rgba(0,0,0,.3); color: black;">
    重要
  </span>
  <div style="margin: 0; padding: 0;">
    この2方式だけでは正確な金額を保証できない場合があります。屋上の状況や建物形状によって作業時間が大きく変わるため、以下で説明する追加料金の要因も含めて総合的に見積もる必要があります。
  </div>
</div>';
    }
    
    private static function getGokaiHtml()
    {
        return '<div class="targetlist_21" style="position: relative; margin: 2em 0; padding: 10px 20px 12px 20px; background: #f0f7ff; border: 2px solid #5b8bd0;">
  <span style="position: absolute; top: -15px; left: 15px; background: #5b8bd0; padding: 0 20px; border-radius: 15px; font-weight: bold; box-shadow: 1px 1px 2px rgba(0,0,0,.3); color: white;">
    【よくある誤解】3階建てのほうが5階建てより安い？
  </span>
  <div style="margin: 0; padding: 0;">
    「3階建てのビルと5階建てのビル、どちらが清掃費用が高いか？」と聞かれたら、多くの方は3階建ての方が安いと答えます。<br>しかし実際は建物の「横幅（間口）」と「ロープを降りる回数」によっては、3階建ての方が高くなるケースがあります。
  </div>
</div>';
    }
    
    private static function getAdviceHtml()
    {
        return '<div class="targetlist_25" style="position: relative; margin: 2em 0; padding: 10px 20px 12px 20px; background: #e8f5e9; border: 2px solid #4caf50;">
  <span style="position: absolute; top: -15px; left: 15px; background: #4caf50; padding: 0 20px; border-radius: 15px; font-weight: bold; box-shadow: 1px 1px 2px rgba(0,0,0,.3); color: white;">
    担当者のアドバイス
  </span>
  <div style="margin: 0; padding: 0;">
    建物の図面や外観写真があれば、見積もり依頼時に一緒に提出すると、業者がロープ本数・降下回数を事前に想定でき、より正確な概算が得られます。電話だけの問い合わせより精度が格段に上がります。
  </div>
</div>';
    }
    
    private static function getNinkuHtml()
    {
        return '<div class="targetlist_20" style="position: relative; margin: 2em 0; padding: 10px 20px 12px 20px; background: #f0f7ff; border: 2px solid #5b8bd0;">
  <span style="position: absolute; top: -15px; left: 15px; background: #5b8bd0; padding: 0 20px; border-radius: 15px; font-weight: bold; box-shadow: 1px 1px 2px rgba(0,0,0,.3); color: white;">
    人工計算の注意点
  </span>
  <div style="margin: 0; padding: 0;">
    作業員が何名必要かは現場によって異なります。<br>「2名×1日」なら50,000〜60,000円、「3名×2日」なら150,000〜180,000円が目安です。
  </div>
</div>';
    }
    
    private static function getKeisanHtml()
    {
        return '<div class="targetlist_19" style="position: relative; margin: 2em 0; padding: 10px 20px 12px 20px; background: #e8f5e9; border: 2px solid #4caf50;">
  <span style="position: absolute; top: -15px; left: 15px; background: #4caf50; padding: 0 20px; border-radius: 15px; font-weight: bold; box-shadow: 1px 1px 2px rgba(0,0,0,.3); color: white;">
    代表的な計算例
  </span>
  <div style="margin: 0; padding: 0;">
    200平米の窓ガラスを両面清掃する場合<br>→ 200平米×150〜200円 = 30,000〜40,000円が料金の目安です。
  </div>
</div>';
    }
    
    private static function getGondoraHtml()
    {
        return '<div class="targetlist_20" style="position: relative; margin: 2em 0; padding: 10px 20px 12px 20px; background: #f0f7ff; border: 2px solid #5b8bd0;">
  <span style="position: absolute; top: -15px; left: 15px; background: #5b8bd0; padding: 0 20px; border-radius: 15px; font-weight: bold; box-shadow: 1px 1px 2px rgba(0,0,0,.3); color: white;">
    ゴンドラ導入の目安
  </span>
  <div style="margin: 0; padding: 0;">
   清掃面積が小さい（200平米程度）場合はゴンドラ設置費が割高になるため、ロープアクセスのほうがコスト効率が高くなるケースがほとんどです。<br>面積が大きくなるほどゴンドラの費用対効果が改善します。
  </div>
</div>';
    }
    
    private static function getTuikaHtml()
    {
        return '<div class="targetlist_21" style="position: relative; margin: 2em 0; padding: 10px 20px 12px 20px; background: #ffebee; border: 2px solid #e53935;">
  <span style="position: absolute; top: -15px; left: 15px; background: #e53935; padding: 0 20px; border-radius: 15px; font-weight: bold; box-shadow: 1px 1px 2px rgba(0,0,0,.3); color: white;">
    追加料金の計算方法
  </span>
  <div style="margin: 0; padding: 0;">
    上記は「作業に余分な時間がかかる」ことが原因です。追加で発生する時間を人件費に換算してプラス計上する形で見積もりを作成します。<br>計算例：セットバックにより作業員がプラス3時間かかる場合、3時間分の人件費（約9,000〜11,000円）を加算します。
  </div>
</div>';
    }
}