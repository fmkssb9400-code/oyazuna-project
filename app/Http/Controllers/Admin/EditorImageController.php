<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EditorImageController extends Controller
{
    /**
     * TinyMCE互換の画像アップロードエンドポイント
     */
    public function upload(Request $request)
    {
        // 認証チェック
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized access'
            ], 401);
        }

        // バリデーション
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120', // 5MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('file');
            
            if (!$file || !$file->isValid()) {
                return response()->json([
                    'error' => 'Invalid file uploaded'
                ], 400);
            }
            
            // ファイル名生成
            $extension = $file->getClientOriginalExtension();
            $filename = 'img_' . time() . '_' . Str::random(8) . '.' . $extension;
            
            // storage/app/public/articles に保存
            $path = $file->storeAs('articles', $filename, 'public');
            
            if (!$path) {
                return response()->json([
                    'error' => 'File storage failed'
                ], 500);
            }
            
            // TinyMCE互換のレスポンス形式
            $url = Storage::disk('public')->url($path);
            
            return response()->json([
                'location' => $url  // TinyMCEが期待するキー
            ]);

        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 画像削除（将来的な拡張用）
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
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $path = $request->input('path');
            
            // セキュリティチェック: articles配下のみ削除許可
            if (!str_starts_with($path, 'articles/')) {
                return response()->json(['error' => 'Forbidden path'], 403);
            }

            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Delete failed'], 500);
        }
    }
}