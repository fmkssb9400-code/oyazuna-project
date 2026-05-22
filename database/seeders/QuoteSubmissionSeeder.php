<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuoteSubmission;

class QuoteSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        $quoteData = [
            // 窓ガラス清掃データ
            [
                'work_type' => 'window',
                'prefecture' => '東京都',
                'quote_date' => '2026-03-15',
                'floor_count' => 10,
                'total_amount' => 208520,
                'work_area' => 396,
                'area_unit' => '㎡',
                'images' => [],
                'work_description' => '定期窓ガラス清掃（年4回）',
                'images' => [],
                'quote_items' => [
                    [
                        'name' => '外装ガラス清掃費',
                        'items' => [
                            [
                                'name' => '① 外装ガラス/外面のみ',
                                'unit' => '㎡',
                                'quantity' => 306.00,
                                'unit_price' => 80,
                                'frequency' => 4,
                                'amount' => 97920
                            ],
                            [
                                'name' => '② 手摺ガラス/両面',
                                'unit' => '㎡',
                                'quantity' => 90.00,
                                'unit_price' => 160,
                                'frequency' => 4,
                                'amount' => 57600
                            ]
                        ]
                    ],
                    [
                        'name' => '地上監視員配置費',
                        'unit' => '人工',
                        'quantity' => 0.50,
                        'unit_price' => 18500,
                        'frequency' => 4,
                        'amount' => 37000
                    ],
                    [
                        'name' => '資材搬入出費',
                        'unit' => '台',
                        'quantity' => 1.00,
                        'unit_price' => 4000,
                        'frequency' => 4,
                        'amount' => 16000
                    ]
                ],
                'status' => 'completed',
                'comment' => '定期清掃契約での見積もりです。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'work_type' => 'window',
                'prefecture' => '大阪府',
                'quote_date' => '2026-03-20',
                'floor_count' => 8,
                'total_amount' => 85000,
                'work_area' => 150,
                'area_unit' => '㎡',
                'images' => [],
                'work_description' => '定期窓ガラス清掃（月1回）',
                'quote_items' => [
                    [
                        'name' => '定期窓ガラス清掃（月1回）',
                        'unit' => '式',
                        'quantity' => 1.00,
                        'unit_price' => 85000,
                        'frequency' => 1,
                        'amount' => 85000
                    ]
                ],
                'status' => 'completed',
                'comment' => '月次定期清掃の見積もりです。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'work_type' => 'window',
                'prefecture' => '愛知県',
                'quote_date' => '2026-03-25',
                'floor_count' => 2,
                'total_amount' => 45000,
                'work_area' => 80,
                'area_unit' => '㎡',
                'images' => [],
                'work_description' => 'スポット窓ガラス清掃',
                'quote_items' => [
                    [
                        'name' => 'スポット窓ガラス清掃',
                        'unit' => '㎡',
                        'quantity' => 80.00,
                        'unit_price' => 450,
                        'frequency' => 1,
                        'amount' => 36000
                    ],
                    [
                        'name' => '出張費',
                        'unit' => '式',
                        'quantity' => 1.00,
                        'unit_price' => 9000,
                        'frequency' => 1,
                        'amount' => 9000
                    ]
                ],
                'status' => 'completed',
                'comment' => 'スポット清掃の見積もりです。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 外壁調査データ  
            [
                'work_type' => 'inspection',
                'prefecture' => '東京都',
                'quote_date' => '2026-03-18',
                'floor_count' => 15,
                'total_amount' => 350000,
                'work_area' => 1200,
                'area_unit' => '㎡',
                'images' => [],
                'work_description' => '外壁全面点検調査',
                'quote_items' => [
                    [
                        'name' => '外壁全面点検調査',
                        'items' => [
                            [
                                'name' => '① 目視点検調査',
                                'unit' => '㎡',
                                'quantity' => 1200.00,
                                'unit_price' => 150,
                                'frequency' => 1,
                                'amount' => 180000
                            ],
                            [
                                'name' => '② 打診調査',
                                'unit' => '㎡',
                                'quantity' => 400.00,
                                'unit_price' => 300,
                                'frequency' => 1,
                                'amount' => 120000
                            ]
                        ]
                    ],
                    [
                        'name' => '調査報告書作成',
                        'unit' => '式',
                        'quantity' => 1.00,
                        'unit_price' => 50000,
                        'frequency' => 1,
                        'amount' => 50000
                    ]
                ],
                'status' => 'completed',
                'comment' => '詳細な外壁調査の見積もりです。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'work_type' => 'inspection',
                'prefecture' => '神奈川県',
                'quote_date' => '2026-03-22',
                'floor_count' => 12,
                'total_amount' => 280000,
                'work_area' => 800,
                'area_unit' => '㎡',
                'images' => [],
                'work_description' => '劣化診断調査',
                'quote_items' => [
                    [
                        'name' => '劣化診断調査',
                        'unit' => '㎡',
                        'quantity' => 800.00,
                        'unit_price' => 350,
                        'frequency' => 1,
                        'amount' => 280000
                    ]
                ],
                'status' => 'completed',
                'comment' => '劣化診断調査の見積もりです。',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // 簡略化のため一部データのみ追加
        foreach ($quoteData as $data) {
            QuoteSubmission::create($data);
        }
    }
}
