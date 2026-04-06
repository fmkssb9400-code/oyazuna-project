<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

/**
 * Filament TinyMCE エディタ用画像アップロードコントローラー
 */
class EditorImageUploadController extends Controller
{
    /**
     * TinyMCE互換の画像アップロードエンドポイント
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        Log::info('TinyMCE画像アップロード開始', [
            'has_file' => $request->hasFile('file'),
            'auth' => Auth::check(),
            'csrf' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing'
        ]);

        // 認証チェック (Filamentログイン中のみ)
        if (!Auth::check()) {
            Log::error('画像アップロード: 認証失敗');
            return response()->json([
                'error' => 'Unauthorized access. Please login to Filament admin.'
            ], 401);
        }

        // バリデーション
        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp,gif',
                'max:5120' // 5MB
            ]
        ]);

        if ($validator->fails()) {
            Log::error('画像アップロード: バリデーション失敗', $validator->errors()->toArray());
            return response()->json([
                'error' => 'Validation failed: ' . $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('file');
            
            if (!$file || !$file->isValid()) {
                Log::error('画像アップロード: 無効なファイル');
                return response()->json([
                    'error' => 'Invalid file uploaded. Please select a valid image file.'
                ], 400);
            }
            
            // ファイル名生成 (重複回避)
            $extension = $file->getClientOriginalExtension();
            $originalName = $file->getClientOriginalName();
            $filename = 'editor_' . time() . '_' . Str::random(8) . '.' . $extension;
            
            Log::info('画像ファイル情報', [
                'original_name' => $originalName,
                'filename' => $filename,
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);
            
            // storage/app/public/articles に保存
            $path = $file->storeAs('articles', $filename, 'public');
            
            if (!$path) {
                Log::error('画像アップロード: ストレージ保存失敗');
                return response()->json([
                    'error' => 'File storage failed. Please check storage permissions.'
                ], 500);
            }
            
            // TinyMCE互換のレスポンス形式で返す
            $url = Storage::disk('public')->url($path);
            
            Log::info('画像アップロード成功', [
                'path' => $path,
                'url' => $url
            ]);
            
            return response()->json([
                'location' => $url  // TinyMCEが期待するキー
            ]);

        } catch (\Exception $e) {
            Log::error('画像アップロード例外: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * アップロード画像削除 (将来的な拡張用)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'path' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);
        }

        try {
            $path = $request->input('path');
            
            // セキュリティチェック: articles配下のみ削除許可
            if (!str_starts_with($path, 'articles/')) {
                return response()->json([
                    'error' => 'Forbidden path'
                ], 403);
            }

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Delete failed'
            ], 500);
        }
    }
}