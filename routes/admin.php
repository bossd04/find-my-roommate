<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ListingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PaymentManagementController;
use App\Http\Controllers\Admin\PaymentReceiptController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\RoommatePreferenceController;
use App\Http\Controllers\Admin\UserReportController;

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
    Route::prefix('users')->name('users.')->group(function () {
        // Specialized User Management Views
        Route::get('id-verification', [UserController::class, 'idVerification'])->name('id-verification');
        Route::get('pending-approvals', [UserController::class, 'pendingApprovals'])->name('pending-approvals');
        
        // Standard User CRUD
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('{user}', [UserController::class, 'show'])->name('show');
        Route::get('{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('{user}', [UserController::class, 'update'])->name('update');
        Route::delete('{user}', [UserController::class, 'destroy'])->name('destroy');
        
        // User Status & Verification Actions
        Route::post('{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('{user}/restore', [UserController::class, 'restore'])->name('restore')->withTrashed();
        Route::delete('{user}/force-delete', [UserController::class, 'forceDelete'])->name('force-delete')->withTrashed();
        Route::post('{user}/id-verify', [UserController::class, 'verifyId'])->name('id-verify');
        Route::post('{user}/id-reject', [UserController::class, 'rejectId'])->name('id-reject');
        Route::post('{user}/approve', [UserController::class, 'approve'])->name('approve');
    });
    
    // Listings Management
    Route::prefix('listings')->name('listings.')->group(function () {
        Route::get('/', [ListingController::class, 'index'])->name('index');
        Route::get('create', [ListingController::class, 'create'])->name('create');
        Route::post('/', [ListingController::class, 'store'])->name('store');
        Route::get('{listing}', [ListingController::class, 'show'])->name('show');
        Route::get('{listing}/edit', [ListingController::class, 'edit'])->name('edit');
        Route::put('{listing}', [ListingController::class, 'update'])->name('update');
        Route::delete('{listing}', [ListingController::class, 'destroy'])->name('destroy');
        Route::patch('{listing}/toggle-status', [ListingController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('{listing}/approve', [ListingController::class, 'approve'])->name('approve');
    });
    
    // Messages Management
    Route::prefix('messages')->name('messages.')->group(function () {
        Route::get('/', [MessageController::class, 'index'])->name('index');
        Route::get('create', [MessageController::class, 'create'])->name('create');
        Route::post('/', [MessageController::class, 'store'])->name('store');
        Route::get('{message}', [MessageController::class, 'show'])->name('show');
        Route::delete('{message}', [MessageController::class, 'destroy'])->name('destroy');
        Route::post('{message}/mark-read', [MessageController::class, 'markAsRead'])->name('mark-read');
        Route::post('mark-all-read', [MessageController::class, 'markAllAsRead'])->name('mark-all-read');
    });
    
    // Serve message images directly (fixes Windows symlink issues)
    Route::get('message-image/{filename}', function ($filename) {
        $path = storage_path('app/public/messages/' . $filename);
        if (!file_exists($path)) {
            abort(404);
        }
        return response()->file($path);
    })->name('message.image');
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('generate', [ReportController::class, 'generate'])->name('generate');
        Route::get('export/{type}/{format?}', [ReportController::class, 'export'])->name('export');
        Route::get('messages', [ReportController::class, 'messages'])->name('messages');
    });
    
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('photo', [ProfileController::class, 'updatePhoto'])->name('photo');
    });
    
    // System Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
        Route::get('design', [SettingController::class, 'design'])->name('design');
        Route::put('design', [SettingController::class, 'updateDesign'])->name('update-design');
        Route::get('email', [SettingController::class, 'email'])->name('email');
        Route::put('email', [SettingController::class, 'updateEmail'])->name('update-email');
        Route::get('payment', [SettingController::class, 'payment'])->name('payment');
        Route::put('payment', [SettingController::class, 'updatePayment'])->name('update-payment');
        Route::get('system', [SettingController::class, 'system'])->name('system');
        Route::post('clear-cache', [SettingController::class, 'clearCache'])->name('clear-cache');
        Route::post('test-email', [SettingController::class, 'sendTestEmail'])->name('test-email');
        Route::post('toggle-test-mode', [SettingController::class, 'toggleTestMode'])->name('toggle-test-mode');
        Route::post('storage-link', [SettingController::class, 'createStorageLink'])->name('storage-link');
    });
    
    // Activity Logs
    Route::prefix('activity-logs')->name('activity_logs.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::get('{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        Route::delete('{activityLog}', [ActivityLogController::class, 'destroy'])->name('destroy');
        Route::post('clear', [ActivityLogController::class, 'clear'])->name('clear');
    });
    
    // Payment Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentManagementController::class, 'index'])->name('index');
        Route::get('create', [PaymentManagementController::class, 'create'])->name('create');
        Route::post('/', [PaymentManagementController::class, 'store'])->name('store');
        Route::get('{payment}', [PaymentManagementController::class, 'show'])->name('show');
        Route::post('{payment}/mark-paid', [PaymentManagementController::class, 'markAsPaid'])->name('mark-paid');
        Route::post('generate-monthly', [PaymentManagementController::class, 'generateMonthlyPayments'])->name('generate-monthly');
        
        // Receipt routes
        Route::get('{payment}/receipt', [PaymentReceiptController::class, 'printReceipt'])->name('receipt');
        Route::get('users/{user}/statement', [PaymentReceiptController::class, 'generateUserStatement'])->name('statement');
    });
    
    // Backup Routes
    Route::prefix('backup')->name('backup.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::post('/', [BackupController::class, 'create'])->name('create');
        Route::get('download/{filename}', [BackupController::class, 'download'])->name('download');
        Route::delete('{filename}', [BackupController::class, 'destroy'])->name('destroy');
    });
    
    // Roommate Preferences Routes
    Route::prefix('preferences')->name('preferences.')->group(function () {
        Route::get('/', [RoommatePreferenceController::class, 'index'])->name('index');
        Route::get('{preference}/edit', [RoommatePreferenceController::class, 'edit'])->name('edit');
        Route::put('{preference}', [RoommatePreferenceController::class, 'update'])->name('update');
        Route::delete('{preference}', [RoommatePreferenceController::class, 'destroy'])->name('destroy');
    });

    // User Reports Routes
    Route::prefix('user-reports')->name('user-reports.')->group(function () {
        Route::get('/', [UserReportController::class, 'index'])->name('index');
        Route::get('users', [UserReportController::class, 'reportedUsers'])->name('users');
        Route::get('{report}', [UserReportController::class, 'show'])->name('show');
        Route::patch('{report}/reviewing', [UserReportController::class, 'markReviewing'])->name('reviewing');
        Route::patch('{report}/resolve', [UserReportController::class, 'resolve'])->name('resolve');
        Route::patch('{report}/dismiss', [UserReportController::class, 'dismiss'])->name('dismiss');
    });

    // Backward Compatibility Alias
    Route::get('settings-alias', [SettingController::class, 'index'])->name('settings');
});