<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $paymentLink->id }}</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            border-bottom: 2px solid #235181;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .invoice-header h1 {
            color: #235181;
            margin: 0;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .invoice-info div {
            flex: 1;
        }
        .invoice-info h3 {
            margin-top: 0;
            color: #235181;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-table th,
        .invoice-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .invoice-table th {
            background-color: #f8f9fa;
            color: #235181;
        }
        .invoice-total {
            text-align: right;
            margin-top: 20px;
        }
        .invoice-total table {
            margin-left: auto;
        }
        .invoice-total td {
            padding: 8px 20px;
        }
        .invoice-total .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #235181;
        }
        .invoice-footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        @media print {
            body {
                background: white;
            }
            .invoice-container {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>Invoice #{{ $paymentLink->id }}</p>
        </div>

        <div class="invoice-info">
            <div>
                <h3>From:</h3>
                <p>
                    <strong>Taeab.com</strong><br>
                    Job Portal UAE<br>
                    Email: {{ config('mail.from.address', 'info@taeab.com') }}
                </p>
            </div>
            <div>
                <h3>To:</h3>
                <p>
                    @if($paymentLink->recipient)
                        <strong>{{ $paymentLink->recipient_type === 'seeker' ? $paymentLink->recipient->name : $paymentLink->recipient->company_name }}</strong><br>
                        {{ $paymentLink->recipient->email }}<br>
                        @if($paymentLink->recipient_type === 'company' && $paymentLink->recipient->address)
                            {{ $paymentLink->recipient->address }}<br>
                            {{ $paymentLink->recipient->city }}, {{ $paymentLink->recipient->country }}
                        @endif
                    @else
                        N/A
                    @endif
                </p>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $paymentLink->description ?? 'Payment' }}</td>
                    <td>1</td>
                    <td>{{ $paymentLink->currency }} {{ number_format($paymentLink->amount, 2) }}</td>
                    <td>{{ $paymentLink->currency }} {{ number_format($paymentLink->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-total">
            <table>
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td>{{ $paymentLink->currency }} {{ number_format($paymentLink->amount, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td class="total-amount">{{ $paymentLink->currency }} {{ number_format($paymentLink->amount, 2) }}</td>
                </tr>
            </table>
        </div>

        <div class="invoice-footer">
            <p>Payment Status: <strong>{{ strtoupper($paymentLink->status) }}</strong></p>
            <p>Payment Date: {{ $paymentLink->paid_at ? $paymentLink->paid_at->format('F d, Y') : 'N/A' }}</p>
            <p>Invoice Generated: {{ now()->format('F d, Y') }}</p>
            @if($paymentLink->stripe_invoice_id)
                <p>Stripe Invoice ID: {{ $paymentLink->stripe_invoice_id }}</p>
            @endif
        </div>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn btn-primary" style="padding: 10px 20px; background: #235181; color: white; border: none; cursor: pointer; border-radius: 5px;">
            Print Invoice
        </button>
    </div>
</body>
</html>

