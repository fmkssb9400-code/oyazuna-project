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
 * TinyMCE エディタ専用画像アップロードController
 * Promise対応のimages_upload_handler用
 */
class EditorUploadController extends Controller
{
    /**
     * TinyMCE Promise対応画像アップロードエンドポイント
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        Log::info('TinyMCE Promise版画像アップロード開始', [
            'user_id' => Auth::id(),
            'has_file' => $request->hasFile('file'),
            'csrf_token' => $request->header('X-CSRF-TOKEN') ? 'present' : 'missing'
        ]);

        try {
            // 1. 認証チェック (管理画面ログイン必須)
            if (!Auth::check()) {
                Log::warning('TinyMCE画像アップロード: 認証失敗');
                return response()->json([
                    'error' => 'Authentication required. Please login to admin panel.'
                ], 401);
            }

            // 2. CSRFトークンチェック
            $token = $request->header('X-CSRF-TOKEN');
            if (!$token || !hash_equals(csrf_token(), $token)) {
                Log::warning('TinyMCE画像アップロード: CSRF検証失敗');
                return response()->json([
                    'error' => 'CSRF token mismatch'
                ], 419);
            }

            // 3. バリデーション
            $validator = Validator::make($request->all(), [
                'file' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp,gif',
                    'max:5120' // 5MB
                ]
            ]);

            if ($validator->fails()) {
                Log::error('TinyMCE画像アップロード: バリデーション失敗', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                return response()->json([
                    'error' => 'File validation failed: ' . $validator->errors()->first()
                ], 422);
            }

            // 3. ファイル処理
            $file = $request->file('file');
            
            if (!$file || !$file->isValid()) {
                Log::error('TinyMCE画像アップロード: 無効なファイル');
                return response()->json([
                    'error' => 'Invalid file uploaded. Please select a valid image file.'
                ], 400);
            }

            // 4. ファイル名生成（重複回避）
            $extension = $file->getClientOriginalExtension();
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = Str::slug($originalName); // ファイル名をクリーンアップ
            $filename = 'editor_' . $cleanName . '_' . time() . '_' . Str::random(6) . '.' . $extension;
            
            Log::info('TinyMCE画像ファイル情報', [
                'original_name' => $file->getClientOriginalName(),
                'generated_filename' => $filename,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);

            // 5. storage/app/public/articles に保存
            $path = $file->storeAs('articles', $filename, 'public');
            
            if (!$path) {
                Log::error('TinyMCE画像アップロード: ストレージ保存失敗');
                return response()->json([
                    'error' => 'File storage failed. Please check server configuration.'
                ], 500);
            }

            // 6. TinyMCE互換のレスポンス生成
            $fullUrl = Storage::disk('public')->url($path);
            
            Log::info('TinyMCE画像アップロード成功', [
                'storage_path' => $path,
                'public_url' => $fullUrl
            ]);

            // TinyMCE Promise resolveで期待される形式
            return response()->json([
                'location' => $fullUrl  // 重要: TinyMCEが期待するキー
            ], 200);

        } catch (\Exception $e) {
            Log::error('TinyMCE画像アップロード例外エラー', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Server error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}