<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fines Report</title>
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
        <h2>Fines Report</h2>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Borrow Code</th>
                <th>Member</th>
                <th>Book</th>
                <th>Late Days</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fines as $fine)
            <tr>
                <td>{{ $fine->borrowing->borrow_code }}</td>
                <td>{{ $fine->borrowing->user->name }}</td>
                <td>{{ $fine->borrowing->book->title }}</td>
                <td>{{ $fine->late_days }}</td>
                <td>Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                <td>{{ ucfirst($fine->payment_status) }}</td>
                <td>{{ $fine->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
