<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentManagementController extends Controller
{
    public function index()
    {
        $payments = Payment::with('user')
            ->orderBy('due_date', 'desc')
            ->paginate(20);
            
        return view('admin.payments.index', compact('payments'));
    }
    
    public function create()
    {
        $users = User::where('is_admin', false)->get();
        return view('admin.payments.create', compact('users'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $payment = Payment::create([
            'user_id' => $validated['user_id'],
            'amount' => $validated['amount'],
            'payment_method' => config('payments.method', 'manual'),
            'payment_date' => null,
            'due_date' => $validated['due_date'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);
        
        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Payment record created successfully!');
    }
    
    public function show(Payment $payment)
    {
        $payment->load('user');
        return view('admin.payments.show', compact('payment'));
    }
    
    public function markAsPaid(Payment $payment)
    {
        $payment->markAsPaid();
        
        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'Payment marked as paid!');
    }
    
    public function generateMonthlyPayments()
    {
        $users = User::where('is_admin', false)->get();
        $monthlyAmount = config('payments.monthly_amount', 1000);
        $dueDateConfig = config('payments.due_date', now()->format('Y-m-d'));
        
        // Parse the configured due date to get day and month
        $dueDate = Carbon::parse($dueDateConfig);
        $currentYear = now()->year;
        
        // Create due date for current year with configured day and month
        $nextDueDate = Carbon::create($currentYear, $dueDate->month, $dueDate->day);
        
        // If the date has passed this year, use next year
        if ($nextDueDate->isPast()) {
            $nextDueDate->addYear();
        }
        
        $createdCount = 0;
        foreach ($users as $user) {
            $existingPayment = Payment::where('user_id', $user->id)
                ->where('due_date', $nextDueDate->toDateString())
                ->first();
                
            if (!$existingPayment) {
                Payment::create([
                    'user_id' => $user->id,
                    'amount' => $monthlyAmount,
                    'payment_method' => config('payments.method', 'manual'),
                    'payment_date' => null,
                    'due_date' => $nextDueDate->toDateString(),
                    'status' => 'pending',
                    'notes' => 'Payment due on ' . $nextDueDate->format('F j, Y'),
                ]);
                $createdCount++;
            }
        }
        
        return redirect()
            ->route('admin.payments.index')
            ->with('success', "Generated {$createdCount} payment records due on {$nextDueDate->format('F j, Y')}!");
    }
}
