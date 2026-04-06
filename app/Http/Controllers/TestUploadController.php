<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestUploadController extends Controller
{
    public function test(Request $request)
    {
        Log::info('=== TEST UPLOAD CONTROLLER ===');
        Log::info('Request method: ' . $request->method());
        Log::info('Request URL: ' . $request->fullUrl());
        Log::info('Request headers: ', $request->headers->all());
        Log::info('Request all: ', $request->all());
        Log::info('Has files: ', $request->hasFile('file') ? 'Yes' : 'No');
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Log::info('File info: ', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
            ]);
            
            $path = $file->store('test-direct', 'public');
            Log::info('File stored at: ' . $path);
            
            return response()->json(['success' => true, 'path' => $path]);
        }
        
        return response()->json(['success' => false, 'error' => 'No file uploaded']);
    }
}