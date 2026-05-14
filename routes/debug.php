<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\UserValidation;

// Debug route for ID images
Route::get('/debug-id-images/{userId}', function ($userId) {
    $user = User::with(['userValidation', 'roommateProfile'])->find($userId);
    
    if (!$user) {
        return response()->json(['error' => 'User not found']);
    }
    
    $v = $user->userValidation;
    
    // Get all possible image paths
    $frontPaths = [
        'user.id_card_front' => $user->id_card_front,
        'validation.id_front_image' => $v?->id_front_image,
    ];
    
    $backPaths = [
        'user.id_card_back' => $user->id_card_back,
        'validation.id_back_image' => $v?->id_back_image,
    ];
    
    // Check if files exist
    $frontExists = [];
    $backExists = [];
    
    foreach ($frontPaths as $key => $path) {
        if (!$path) continue;
        
        $possiblePaths = [
            'storage/app/public/' . $path,
            'storage/app/public/validations/' . basename($path),
            'storage/app/public/id_cards/' . basename($path),
        ];
        
        foreach ($possiblePaths as $checkPath) {
            $fullPath = base_path($checkPath);
            $frontExists[$key][$checkPath] = [
                'exists' => file_exists($fullPath),
                'full_path' => $fullPath,
            ];
        }
    }
    
    foreach ($backPaths as $key => $path) {
        if (!$path) continue;
        
        $possiblePaths = [
            'storage/app/public/' . $path,
            'storage/app/public/validations/' . basename($path),
            'storage/app/public/id_cards/' . basename($path),
        ];
        
        foreach ($possiblePaths as $checkPath) {
            $fullPath = base_path($checkPath);
            $backExists[$key][$checkPath] = [
                'exists' => file_exists($fullPath),
                'full_path' => $fullPath,
            ];
        }
    }
    
    // Check what the payload generates
    $payload = $user->toAdminIdReviewPayload();
    
    return response()->json([
        'user_id' => $user->id,
        'user_name' => $user->full_name,
        'verification_status' => $user->verification_status,
        'validation_status' => $v?->status,
        'front_paths_raw' => $frontPaths,
        'back_paths_raw' => $backPaths,
        'front_files_exist' => $frontExists,
        'back_files_exist' => $backExists,
        'payload_imageFront' => $payload['imageFront'],
        'payload_imageBack' => $payload['imageBack'],
        'storage_base_path' => storage_path('app/public'),
        'validations_path_exists' => file_exists(storage_path('app/public/validations')),
        'id_cards_path_exists' => file_exists(storage_path('app/public/id_cards')),
    ]);
});
