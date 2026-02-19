<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $receipt_number }}</title>
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
            
            .receipt-container {
                max-width: 600px;
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
            
            .receipt-title {
                font-size: 20px;
                font-weight: bold;
                margin: 20px 0;
                text-transform: uppercase;
            }
            
            .receipt-info {
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
            
            .payment-details {
                margin: 30px 0;
            }
            
            .detail-row {
                display: flex;
                justify-content: space-between;
                padding: 10px 0;
                border-bottom: 1px solid #ddd;
            }
            
            .detail-row:last-child {
                border-bottom: 2px solid #333;
                font-weight: bold;
                font-size: 18px;
            }
            
            .footer {
                margin-top: 40px;
                text-align: center;
                border-top: 1px solid #ddd;
                padding-top: 20px;
            }
            
            .status-paid {
                color: #28a745;
                font-weight: bold;
                text-transform: uppercase;
            }
            
            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 100px;
                color: rgba(0, 128, 0, 0.1);
                font-weight: bold;
                z-index: -1;
            }
        }
        
        @media screen {
            body {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 20px;
                background: #f5f5f5;
            }
            
            .receipt-container {
                max-width: 600px;
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
    @if($payment->status === 'paid')
        <div class="watermark">PAID</div>
    @endif
    
    <div class="receipt-container">
        <!-- Header -->
        <div class="header">
            <div class="company-name">{{ $company['name'] }}</div>
            <div>{{ $company['address'] }}</div>
            <div>{{ $company['phone'] }}</div>
            <div>{{ $company['email'] }}</div>
        </div>
        
        <!-- Receipt Title -->
        <div class="receipt-title">OFFICIAL RECEIPT</div>
        
        <!-- Receipt Info -->
        <div class="receipt-info">
            <div class="info-section">
                <div class="info-label">Receipt Number:</div>
                <div>{{ $receipt_number }}</div>
            </div>
            <div class="info-section">
                <div class="info-label">Date Generated:</div>
                <div>{{ $generated_date }}</div>
            </div>
        </div>
        
        <!-- Customer Information -->
        <div class="receipt-info">
            <div class="info-section">
                <div class="info-label">Customer Name:</div>
                <div>{{ $payment->user->name }}</div>
                <div class="info-label" style="margin-top: 10px;">Email:</div>
                <div>{{ $payment->user->email }}</div>
            </div>
            <div class="info-section">
                <div class="info-label">Payment Status:</div>
                <div class="{{ $payment->status === 'paid' ? 'status-paid' : '' }}">
                    {{ ucfirst($payment->status) }}
                </div>
                @if($payment->payment_date)
                    <div class="info-label" style="margin-top: 10px;">Payment Date:</div>
                    <div>{{ $payment->payment_date->format('F d, Y') }}</div>
                @endif
            </div>
        </div>
        
        <!-- Payment Details -->
        <div class="payment-details">
            <div class="detail-row">
                <span>Payment Description:</span>
                <span>{{ $payment->notes ?? 'Monthly Payment' }}</span>
            </div>
            <div class="detail-row">
                <span>Due Date:</span>
                <span>{{ $payment->due_date->format('F d, Y') }}</span>
            </div>
            <div class="detail-row">
                <span>Payment Method:</span>
                <span>{{ ucfirst($payment->payment_method) }}</span>
            </div>
            <div class="detail-row">
                <span>Subtotal:</span>
                <span>₱{{ number_format($payment->amount, 2) }}</span>
            </div>
            <div class="detail-row">
                <span>Total Amount Paid:</span>
                <span>₱{{ number_format($payment->amount, 2) }}</span>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div><strong>Thank you for your payment!</strong></div>
            <div>This receipt serves as proof of payment.</div>
            <div style="margin-top: 20px; font-size: 12px;">
                Generated on {{ $generated_date }} | Receipt #{{ $receipt_number }}
            </div>
        </div>
    </div>
    
    <!-- Print Button -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Print Receipt
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            <i class="fas fa-times"></i> Close
        </button>
    </div>
</body>
</html>
