<?php

require __DIR__.'/bootstrap/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

// Boot the application
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get the admin user
$admin = \App\Models\User::where('is_admin', true)->first();

if ($admin) {
    $admin->email = 'admin@example.com';
    $admin->password = bcrypt('admin123');
    $admin->is_active = true;
    $admin->save();
    
    echo "Admin user updated successfully!\n";
    echo "Email: " . $admin->email . "\n";
    echo "Password: admin123\n";
} else {
    // Create admin user if not exists
    $admin = \App\Models\User::create([
        'first_name' => 'Admin',
        'last_name' => 'User',
        'email' => 'admin@example.com',
        'password' => bcrypt('admin123'),
        'is_admin' => true,
        'is_active' => true,
        'email_verified_at' => now(),
    ]);
    
    echo "Admin user created successfully!\n";
    echo "Email: admin@example.com\n";
    echo "Password: admin123\n";
}