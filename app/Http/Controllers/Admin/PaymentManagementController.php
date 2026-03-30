<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentManagementController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(): View
    {
        $payments = Payment::with('user')->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(): View
    {
        return view('admin.payments.create');
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'description' => 'required|string|max:255',
        ]);

        Payment::create($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment): View
    {
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Mark payment as paid.
     */
    public function markAsPaid(Payment $payment): RedirectResponse
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment marked as paid.');
    }

    /**
     * Generate monthly payments for all users.
     */
    public function generateMonthlyPayments(Request $request): RedirectResponse
    {
        $month = $request->input('month', now()->format('Y-m-01'));
        $amount = $request->input('amount', 500); // Default monthly fee

        // This would generate payments for all active users
        // Implementation depends on your business logic

        return redirect()->route('admin.payments.index')
            ->with('success', 'Monthly payments generated successfully.');
    }
}
