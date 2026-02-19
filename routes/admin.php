<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ListingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PaymentManagementController;
use App\Http\Controllers\Admin\PaymentReceiptController;
use App\Http\Controllers\Admin\BackupController;

// Admin Auth Routes
Route::middleware('web')->group(function () {
    // Guest routes (not authenticated)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/admin/login', [\App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'create'])
            ->name('admin.login');

        Route::post('/admin/login', [\App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'store'])
            ->name('admin.login.submit');
    });

    // Authenticated admin routes
    Route::middleware(['auth:admin', 'admin'])->group(function () {
        // Admin Logout
        Route::post('/admin/logout', [\App\Http\Controllers\Admin\Auth\AuthenticatedSessionController::class, 'destroy'])
            ->name('admin.logout');
    });
});

// Admin Protected Routes
Route::middleware(['web', 'auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Redirect /admin to /admin/dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->name('index');
    
    // User Management
    Route::resource('users', UserController::class)->except(['show']);
    
    // Additional User Routes
    Route::prefix('users')->name('users.')->group(function () {
        // Show user details
        Route::get('{user}', [UserController::class, 'show'])->name('show');
        
        // Toggle user status
        Route::post('{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('toggle-status');
            
        // Restore soft-deleted user
        Route::post('{user}/restore', [UserController::class, 'restore'])
            ->name('restore')
            ->withTrashed();
            
        // Permanently delete user
        Route::delete('{user}/force-delete', [UserController::class, 'forceDelete'])
            ->name('force-delete')
            ->withTrashed();
    });
    
    // Listings Management
    Route::resource('listings', ListingController::class);
    
    // Messages Management
    Route::resource('messages', MessageController::class);
    Route::post('messages/{message}/mark-read', [MessageController::class, 'markAsRead'])->name('messages.mark-read');
    Route::post('messages/mark-all-read', [MessageController::class, 'markAllAsRead'])->name('messages.mark-all-read');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/generate', [ReportController::class, 'generate'])->name('generate');
        Route::get('/export/{type}/{format?}', [ReportController::class, 'export'])->name('export');
        Route::get('/messages', [ReportController::class, 'messages'])->name('messages');
        Route::post('/generate', [ReportController::class, 'generate'])->name('generate');
    });
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('photo');
    });
    
    // System Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
        Route::post('/clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
        Route::get('/system', [SettingController::class, 'system'])->name('system');
        Route::get('/email', [SettingController::class, 'email'])->name('email');
        Route::put('/email', [SettingController::class, 'updateEmail'])->name('update-email');
        Route::post('/test-email', [SettingController::class, 'sendTestEmail'])->name('test-email');
        Route::get('/payment', [SettingController::class, 'payment'])->name('payment');
        Route::put('/payment', [SettingController::class, 'updatePayment'])->name('update-payment');
        Route::post('/toggle-test-mode', [SettingController::class, 'toggleTestMode'])->name('toggle-test-mode');
        
        // Storage Link
        Route::post('/storage-link', [SettingController::class, 'createStorageLink'])
            ->name('storage-link');
    });
    
    // Listings Management
    Route::prefix('listings')->name('listings.')->group(function () {
        Route::get('/', [ListingController::class, 'index'])->name('index');
        Route::get('/create', [ListingController::class, 'create'])->name('create');
        Route::post('/', [ListingController::class, 'store'])->name('store');
        Route::get('/{listing}/edit', [ListingController::class, 'edit'])->name('edit');
        Route::put('/{listing}', [ListingController::class, 'update'])->name('update');
        Route::delete('/{listing}', [ListingController::class, 'destroy'])->name('destroy');
        Route::patch('/{listing}/toggle-status', [ListingController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/generate', [ReportController::class, 'generate'])->name('generate');
    });

    // System Settings Group
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
        Route::get('/email', [SettingController::class, 'email'])->name('email');
        Route::put('/email', [SettingController::class, 'updateEmail'])->name('update-email');
        Route::get('/payment', [SettingController::class, 'payment'])->name('payment');
        Route::put('/payment', [SettingController::class, 'updatePayment'])->name('update-payment');
        Route::get('/system', [SettingController::class, 'system'])->name('system');
        Route::post('/clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
        Route::post('/test-email', [SettingController::class, 'sendTestEmail'])->name('test-email');
        Route::post('/toggle-test-mode', [SettingController::class, 'toggleTestMode'])->name('toggle-test-mode');
        Route::post('/storage-link', [SettingController::class, 'createStorageLink'])->name('storage-link');
    });
    
    // Alias for settings index for backward compatibility
    Route::get('settings', [SettingController::class, 'index'])->name('settings');
    
    // Activity Logs
    Route::prefix('activity-logs')->name('activity_logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        Route::delete('/{activityLog}', [ActivityLogController::class, 'destroy'])->name('destroy');
        Route::post('/clear', [ActivityLogController::class, 'clear'])->name('clear');
    });
    
    // Payment Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentManagementController::class, 'index'])->name('index');
        Route::get('/create', [PaymentManagementController::class, 'create'])->name('create');
        Route::post('/', [PaymentManagementController::class, 'store'])->name('store');
        Route::get('/{payment}', [PaymentManagementController::class, 'show'])->name('show');
        Route::post('/{payment}/mark-paid', [PaymentManagementController::class, 'markAsPaid'])->name('mark-paid');
        Route::post('/generate-monthly', [PaymentManagementController::class, 'generateMonthlyPayments'])->name('generate-monthly');
        
        // Receipt routes
        Route::get('/{payment}/receipt', [PaymentReceiptController::class, 'printReceipt'])->name('receipt');
        Route::get('/users/{user}/statement', [PaymentReceiptController::class, 'generateUserStatement'])->name('statement');
    });
    
    // Backup Routes
    Route::prefix('backup')->name('backup.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/', [BackupController::class, 'create'])->name('create');
        Route::get('/download/{filename}', [BackupController::class, 'download'])->name('download');
        Route::delete('/{filename}', [BackupController::class, 'destroy'])->name('destroy');
    });
    
    // Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::put('/', [SettingController::class, 'update'])->name('update');
    });
    
    // Fallback route for admin
    Route::get('/{any}', function () {
        return redirect()->route('admin.dashboard');
    })->where('any', '.*');
});