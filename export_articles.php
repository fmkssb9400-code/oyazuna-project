<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$articles = \App\Models\Article::query()->get()->map(function ($article) {
    return $article->toArray();
})->all();

file_put_contents(
    __DIR__ . '/articles_export.json',
    json_encode($articles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
);

echo 'articles: ' . count($articles) . PHP_EOL;
echo 'exported: ' . __DIR__ . '/articles_export.json' . PHP_EOL;
