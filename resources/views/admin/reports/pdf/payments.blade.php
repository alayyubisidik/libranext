<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payments Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Payments Report</h2>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Member</th>
                <th>Borrow Code</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Paid At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->order_id }}</td>
                <td>{{ $payment->fine->borrowing->user->name }}</td>
                <td>{{ $payment->fine->borrowing->borrow_code }}</td>
                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                <td>{{ ucfirst($payment->payment_method) }}</td>
                <td>{{ ucfirst($payment->status) }}</td>
                <td>{{ $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
