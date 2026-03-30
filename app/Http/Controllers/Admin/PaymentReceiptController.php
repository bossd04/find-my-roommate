<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentReceiptController extends Controller
{
    /**
     * Print payment receipt.
     */
    public function printReceipt(Payment $payment)
    {
        return view('admin.payments.receipt', compact('payment'));
    }
}
