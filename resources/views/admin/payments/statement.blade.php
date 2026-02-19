<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Statement - {{ $user->name }}</title>
    <style>
        @media print {
            body {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 20px;
                background: white;
            }
            
            .no-print {
                display: none !important;
            }
            
            .statement-container {
                max-width: 800px;
                margin: 0 auto;
                border: 2px solid #333;
                padding: 30px;
                background: white;
            }
            
            .header {
                text-align: center;
                border-bottom: 2px solid #333;
                padding-bottom: 20px;
                margin-bottom: 30px;
            }
            
            .company-name {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 10px;
            }
            
            .statement-title {
                font-size: 20px;
                font-weight: bold;
                margin: 20px 0;
                text-transform: uppercase;
            }
            
            .customer-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 30px;
            }
            
            .info-section {
                flex: 1;
            }
            
            .info-label {
                font-weight: bold;
                margin-bottom: 5px;
            }
            
            .payments-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            
            .payments-table th,
            .payments-table td {
                border: 1px solid #ddd;
                padding: 12px;
                text-align: left;
            }
            
            .payments-table th {
                background-color: #f8f9fa;
                font-weight: bold;
                border-bottom: 2px solid #333;
            }
            
            .status-paid {
                color: #28a745;
                font-weight: bold;
            }
            
            .status-pending {
                color: #ffc107;
                font-weight: bold;
            }
            
            .status-overdue {
                color: #dc3545;
                font-weight: bold;
            }
            
            .summary {
                margin-top: 30px;
                padding: 20px;
                background-color: #f8f9fa;
                border: 1px solid #ddd;
            }
            
            .summary-row {
                display: flex;
                justify-content: space-between;
                margin-bottom: 10px;
            }
            
            .summary-row:last-child {
                font-weight: bold;
                font-size: 18px;
                border-top: 2px solid #333;
                padding-top: 10px;
            }
            
            .footer {
                margin-top: 40px;
                text-align: center;
                border-top: 1px solid #ddd;
                padding-top: 20px;
            }
        }
        
        @media screen {
            body {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 20px;
                background: #f5f5f5;
            }
            
            .statement-container {
                max-width: 800px;
                margin: 0 auto;
                border: 2px solid #333;
                padding: 30px;
                background: white;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body>
    <div class="statement-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $company['name'] }}</div>
            <div>{{ $company['address'] }}</div>
            <div>{{ $company['phone'] }}</div>
            <div>{{ $company['email'] }}</div>
        </div>
        
        <!-- Statement Title -->
        <div class="statement-title">Payment Statement</div>
        
        <!-- Customer Information -->
        <div class="customer-info">
            <div class="info-section">
                <div class="info-label">Customer Name:</div>
                <div>{{ $user->name }}</div>
                <div class="info-label" style="margin-top: 10px;">Email:</div>
                <div>{{ $user->email }}</div>
            </div>
            <div class="info-section">
                <div class="info-label">Statement Period:</div>
                <div>{{ $statement_period }}</div>
                <div class="info-label" style="margin-top: 10px;">Date Generated:</div>
                <div>{{ $generated_date }}</div>
            </div>
        </div>
        
        <!-- Payments Table -->
        <table class="payments-table">
            <thead>
                <tr>
                    <th>Due Date</th>
                    <th>Payment Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment Method</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->due_date->format('M d, Y') }}</td>
                        <td>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '-' }}</td>
                        <td>₱{{ number_format($payment->amount, 2) }}</td>
                        <td>
                            <span class="status-{{ $payment->status }}">
                                @if($payment->status === 'paid')
                                    Paid
                                @elseif($payment->isOverdue())
                                    Overdue
                                @else
                                    Pending
                                @endif
                            </span>
                        </td>
                        <td>{{ ucfirst($payment->payment_method) }}</td>
                        <td>{{ $payment->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            No payment records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span>Total Payments:</span>
                <span>{{ $payments->count() }}</span>
            </div>
            <div class="summary-row">
                <span>Paid Amount:</span>
                <span>₱{{ number_format($payments->where('status', 'paid')->sum('amount'), 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Pending Amount:</span>
                <span>₱{{ number_format($payments->where('status', 'pending')->sum('amount'), 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Total Amount:</span>
                <span>₱{{ number_format($payments->sum('amount'), 2) }}</span>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div><strong>Payment Statement Summary</strong></div>
            <div>This statement shows all payment transactions for the specified period.</div>
            <div style="margin-top: 20px; font-size: 12px;">
                Generated on {{ $generated_date }} | {{ $company['name'] }}
            </div>
        </div>
    </div>
    
    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Print Statement
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            <i class="fas fa-times"></i> Close
        </button>
    </div>
</body>
</html>
