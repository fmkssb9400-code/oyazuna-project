<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = [
    'prefectures',
    'service_methods',
    'building_types',
    'service_categories',
    'companies',
    'company_assets',
    'company_prefecture',
    'company_service_method',
    'company_building_type',
    'company_service_category',
];

$data = [];

foreach ($tables as $table) {
    $rows = Illuminate\Support\Facades\DB::table($table)->get()->map(function ($row) {
        return (array) $row;
    })->all();

    $data[$table] = $rows;
    echo $table . ': ' . count($rows) . PHP_EOL;
}

file_put_contents(
    __DIR__ . '/oyazuna_data.json',
    json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
);

echo 'exported: ' . __DIR__ . '/oyazuna_data.json' . PHP_EOL;
