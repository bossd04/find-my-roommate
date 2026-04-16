<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\RoommateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Store a new message
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

// Get messages between two users
Route::get('/messages/{user}', [MessageController::class, 'index'])->name('messages.index');

// Mark a message as read
Route::post('/messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.mark-as-read');

// Get unread message count
Route::get('/messages/{user}/unread', [MessageController::class, 'unread'])->name('messages.unread');

// Mark all messages as read
Route::post('/messages/{user}/read-all', [MessageController::class, 'markAllAsRead'])->name('messages.mark-all-as-read');

// Search roommates by location
Route::get('/roommates/search', [RoommateController::class, 'searchByLocation'])->name('roommates.search');
