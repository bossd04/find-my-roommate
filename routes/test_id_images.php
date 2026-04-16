<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

// Direct ID image test - shows all info for a user
Route::get('/test-id-images', function () {
    // Get a user with pending verification and ID images
    $user = User::with('userValidation')
        ->where(function($q) {
            $q->whereNotNull('id_card_front')
              ->orWhereHas('userValidation', function($qv) {
                  $qv->whereNotNull('id_front_image');
              });
        })
        ->first();
    
    if (!$user) {
        return '<h1>No users with ID images found</h1><p>Check database for users with id_card_front or validation.id_front_image</p>';
    }
    
    $v = $user->userValidation;
    
    // Check what's stored in BOTH locations
    $userFrontPath = $user->id_card_front;
    $userBackPath = $user->id_card_back;
    $valFrontPath = $v?->id_front_image;
    $valBackPath = $v?->id_back_image;
    
    // Use the same logic as toAdminIdReviewPayload
    $frontPath = $userFrontPath ?: $valFrontPath;
    $backPath = $userBackPath ?: $valBackPath;
    
    // Build test URLs
    $frontUrl = $frontPath ? route('id.card.serve', ['path' => $frontPath]) : null;
    $backUrl = $backPath ? route('id.card.serve', ['path' => $backPath]) : null;
    
    // Check if files exist
    $disk = Storage::disk('public');
    
    $html = '<!DOCTYPE html>
<html>
<head>
    <title>ID Image Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ccc; }
        .error { color: red; }
        .success { color: green; }
        img { max-width: 400px; max-height: 300px; border: 2px solid #333; margin: 10px; }
        pre { background: #f4f4f4; padding: 10px; overflow-x: auto; }
        table { border-collapse: collapse; width: 100%; }
        td, th { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .exists { background: #d4edda; }
        .missing { background: #f8d7da; }
    </style>
</head>
<body>
    <h1>ID Image Diagnostic - User: ' . $user->full_name . ' (ID: ' . $user->id . ')</h1>
    
    <div class="section">
        <h2>Database Values</h2>
        <table>
            <tr><th>Location</th><th>Front Path</th><th>Back Path</th></tr>
            <tr>
                <td>User Model</td>
                <td>' . ($userFrontPath ?: '<em>null</em>') . '</td>
                <td>' . ($userBackPath ?: '<em>null</em>') . '</td>
            </tr>
            <tr>
                <td>UserValidation Model</td>
                <td>' . ($valFrontPath ?: '<em>null</em>') . '</td>
                <td>' . ($valBackPath ?: '<em>null</em>') . '</td>
            </tr>
            <tr style="background:#e3f2fd">
                <td><strong>Active (used for display)</strong></td>
                <td><strong>' . ($frontPath ?: '<em>null</em>') . '</strong></td>
                <td><strong>' . ($backPath ?: '<em>null</em>') . '</strong></td>
            </tr>
        </table>
    </div>
    
    <div class="section">
        <h2>Generated URLs</h2>
        <pre>' . json_encode([
            'front_url' => $frontUrl,
            'back_url' => $backUrl,
        ], JSON_PRETTY_PRINT) . '</pre>
    </div>
    
    <div class="section">
        <h2>Storage Existence Check</h2>
        <table>
            <tr><th>Path</th><th>Exists in Storage?</th></tr>';
    
    $allPaths = array_filter([
        $userFrontPath, $userBackPath, $valFrontPath, $valBackPath, $frontPath, $backPath
    ]);
    
    $checked = [];
    foreach ($allPaths as $path) {
        if (in_array($path, $checked)) continue;
        $checked[] = $path;
        
        $exists = $disk->exists($path);
        $class = $exists ? 'exists' : 'missing';
        $html .= '<tr class="' . $class . '"><td>' . $path . '</td><td>' . ($exists ? 'YES ✓' : 'NO ✗') . '</td></tr>';
        
        // Also check alternative locations
        $basename = basename($path);
        $altPaths = [
            'id_cards/' . $basename,
            'validations/' . $basename,
        ];
        foreach ($altPaths as $alt) {
            if ($alt === $path) continue;
            $altExists = $disk->exists($alt);
            $altClass = $altExists ? 'exists' : 'missing';
            $html .= '<tr class="' . $altClass . '"><td>&nbsp;&nbsp;→ ' . $alt . '</td><td>' . ($altExists ? 'YES ✓' : 'NO ✗') . '</td></tr>';
        }
    }
    
    $html .= '</table>
    </div>
    
    <div class="section">
        <h2>Direct File System Check</h2>
        <table>
            <tr><th>Path</th><th>Exists?</th><th>Readable?</th></tr>';
    
    foreach ($allPaths as $path) {
        if (!$path) continue;
        $fullPath = storage_path('app/public/' . $path);
        $exists = file_exists($fullPath);
        $readable = is_readable($fullPath);
        $class = ($exists && $readable) ? 'exists' : 'missing';
        $html .= '<tr class="' . $class . '">
            <td>' . $fullPath . '</td>
            <td>' . ($exists ? 'YES' : 'NO') . '</td>
            <td>' . ($readable ? 'YES' : 'NO') . '</td>
        </tr>';
    }
    
    $html .= '</table>
    </div>
    
    <div class="section">
        <h2>Image Test - Front</h2>
        <p>URL: ' . ($frontUrl ?? 'N/A') . '</p>';
    
    if ($frontUrl) {
        $html .= '<img src="' . $frontUrl . '" alt="Front ID" onerror="this.style.border=\'4px solid red\'; this.nextElementSibling.style.display=\'block\';">
        <p class="error" style="display:none;"><strong>Image failed to load! Check console for errors.</strong></p>';
    } else {
        $html .= '<p class="error">No front image URL generated</p>';
    }
    
    $html .= '</div>
    
    <div class="section">
        <h2>Image Test - Back</h2>
        <p>URL: ' . ($backUrl ?? 'N/A') . '</p>';
    
    if ($backUrl) {
        $html .= '<img src="' . $backUrl . '" alt="Back ID" onerror="this.style.border=\'4px solid red\'; this.nextElementSibling.style.display=\'block\';">
        <p class="error" style="display:none;"><strong>Image failed to load! Check console for errors.</strong></p>';
    } else {
        $html .= '<p class="error">No back image URL generated</p>';
    }
    
    $html .= '</div>
    
</body>
</html>';
    
    return $html;
});
