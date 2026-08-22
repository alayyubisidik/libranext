@extends('dashboard.layouts.app')

@section('title', 'Pay with Midtrans')

@section('content')
<div class="mb-6">
    <a href="{{ user()->hasRole('admin') ? route('dashboard.fines.show', $fine) : route('dashboard.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back
    </a>
</div>

<div class="max-w-2xl mx-auto">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 text-center">
            <h3 class="text-lg font-semibold text-gray-900">Complete Your Payment</h3>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <p class="text-sm text-gray-600 mb-2">You are about to pay the fine for the overdue book <strong>{{ $fine->borrowing->book->title }}</strong>.</p>
                <div class="flex justify-between items-center py-3 border-y border-gray-200 mt-4">
                    <span class="text-gray-600 font-medium">Total Amount</span>
                    <span class="text-2xl font-bold text-gray-900">Rp{{ number_format($fine->amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="text-center">
                <button type="button" id="pay-button" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm w-full sm:w-auto">
                    Pay Now
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    var payButton = document.getElementById('pay-button');
    var redirectUrl = '{{ user()->hasRole("admin") ? route("dashboard.fines.show", $fine) : route("dashboard.index") }}';
    var callbackUrl = '{{ route("dashboard.fines.midtrans-callback") }}';
    var csrfToken = '{{ csrf_token() }}';

    function notifyServer(result, callback) {
        fetch(callbackUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                order_id: result.order_id,
                transaction_status: result.transaction_status,
                fraud_status: result.fraud_status ?? null,
            }),
        }).finally(function () {
            if (callback) callback();
        });
    }

    if (payButton) {
        payButton.onclick = function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    notifyServer(result, function () {
                        window.location.href = redirectUrl;
                    });
                },
                onPending: function (result) {
                    window.location.href = redirectUrl;
                },
                onError: function (result) {
                    window.location.href = redirectUrl;
                },
                onClose: function () {
                }
            });
        };
    }
</script>
@endpush