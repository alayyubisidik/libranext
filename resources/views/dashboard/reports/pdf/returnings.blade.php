<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Returnings Report</title>
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
        <h2>Returnings Report</h2>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Borrow Code</th>
                <th>Member</th>
                <th>Book</th>
                <th>Borrow Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Processed By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returnings as $borrowing)
            <tr>
                <td>{{ $borrowing->borrow_code }}</td>
                <td>{{ $borrowing->user->name }}</td>
                <td>{{ $borrowing->book->title }}</td>
                <td>{{ $borrowing->borrow_date->format('Y-m-d') }}</td>
                <td>{{ $borrowing->due_date->format('Y-m-d') }}</td>
                <td>{{ $borrowing->returned_at->format('Y-m-d') }}</td>
                <td>{{ $borrowing->processedBy ? $borrowing->processedBy->name : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
