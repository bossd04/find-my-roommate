<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentReceiptController extends Controller
{
    public function printReceipt(Payment $payment)
    {
        // Load payment with user relationship
        $payment->load('user');
        
        // Return view for printing
        return view('admin.payments.receipt-print', [
            'payment' => $payment,
            'company' => [
                'name' => config('app.name', 'Find My Roommate'),
                'address' => config('company.address', '123 Main Street, City'),
                'phone' => config('company.phone', '+63 912 345 6789'),
                'email' => config('company.email', 'info@findmyroommate.com'),
            ],
            'receipt_number' => 'REC-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT),
            'generated_date' => now()->format('F d, Y'),
        ]);
    }
    
    public function generateUserStatement(User $user)
    {
        // Get all payments for the user
        $payments = Payment::where('user_id', $user->id)
            ->orderBy('due_date', 'desc')
            ->get();
            
        // Return view for printing
        return view('admin.payments.statement', [
            'user' => $user,
            'payments' => $payments,
            'company' => [
                'name' => config('app.name', 'Find My Roommate'),
                'address' => config('company.address', '123 Main Street, City'),
                'phone' => config('company.phone', '+63 912 345 6789'),
                'email' => config('company.email', 'info@findmyroommate.com'),
            ],
            'statement_period' => 'All payments as of ' . now()->format('F d, Y'),
            'generated_date' => now()->format('F d, Y'),
        ]);
    }
}
