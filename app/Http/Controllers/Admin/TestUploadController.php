<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestUploadController extends Controller
{
    public function upload(Request $request)
    {
        try {
            if (!$request->hasFile('image')) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }
            
            $file = $request->file('image');
            $filename = 'test_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Use move instead of Laravel storage
            $destination = public_path('uploads');
            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }
            
            if ($file->move($destination, $filename)) {
                $url = asset('uploads/' . $filename);
                
                return response()->json([
                    'success' => true,
                    'url' => $url,
                    'filename' => $filename
                ]);
            } else {
                return response()->json(['error' => 'File move failed'], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}