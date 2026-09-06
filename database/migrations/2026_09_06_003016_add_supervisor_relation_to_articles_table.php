<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->foreignId('supervisor_id')->nullable()->after('is_featured')->constrained()->nullOnDelete();
            $table->boolean('show_supervisor')->default(false)->after('supervisor_id');
        });

        // 既存の記事に直接入力されていた監修者情報を、supervisorsテーブルに移行する。
        // 同一の名前・肩書き・紹介文の組み合わせは1件の監修者にまとめる（アバターは最初に見つかったものを採用）。
        $articles = DB::table('articles')->whereNotNull('supervisor_name')->get();
        $supervisorIdsByKey = [];

        foreach ($articles as $article) {
            $key = md5($article->supervisor_name . '|' . $article->supervisor_title . '|' . $article->supervisor_description);

            if (!isset($supervisorIdsByKey[$key])) {
                $supervisorIdsByKey[$key] = DB::table('supervisors')->insertGetId([
                    'name' => $article->supervisor_name,
                    'title' => $article->supervisor_title,
                    'description' => $article->supervisor_description,
                    'avatar' => $article->supervisor_avatar,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('articles')
                ->where('id', $article->id)
                ->update([
                    'supervisor_id' => $supervisorIdsByKey[$key],
                    'show_supervisor' => true,
                ]);
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['supervisor_name', 'supervisor_title', 'supervisor_description', 'supervisor_avatar']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('supervisor_name')->nullable()->after('is_featured');
            $table->string('supervisor_title')->nullable()->after('supervisor_name');
            $table->text('supervisor_description')->nullable()->after('supervisor_title');
            $table->string('supervisor_avatar')->nullable()->after('supervisor_description');
        });

        $articles = DB::table('articles')->whereNotNull('supervisor_id')->get();

        foreach ($articles as $article) {
            $supervisor = DB::table('supervisors')->find($article->supervisor_id);

            if ($supervisor) {
                DB::table('articles')
                    ->where('id', $article->id)
                    ->update([
                        'supervisor_name' => $supervisor->name,
                        'supervisor_title' => $supervisor->title,
                        'supervisor_description' => $supervisor->description,
                        'supervisor_avatar' => $supervisor->avatar,
                    ]);
            }
        }

        Schema::table('articles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supervisor_id');
            $table->dropColumn('show_supervisor');
        });
    }
};
