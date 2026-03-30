<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing application functionality...\n";

try {
    $userCount = App\Models\User::count();
    echo "✅ Database connection OK\n";
    echo "✅ User count: $userCount\n";
    
    // Test routes
    $routes = app('router')->getRoutes();
    $routeCount = 0;
    foreach ($routes as $route) {
        $routeCount++;
    }
    echo "✅ Routes loaded: $routeCount routes\n";
    
    // Test config
    $dbConfig = config('database.default');
    echo "✅ Database connection: $dbConfig\n";
    
    echo "\n=== APPLICATION STATUS ===\n";
    echo "✅ Laravel Framework: " . app()->version() . "\n";
    echo "✅ PHP Version: " . phpversion() . "\n";
    echo "✅ Environment: " . app()->environment() . "\n";
    echo "✅ All systems operational!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "❌ File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
