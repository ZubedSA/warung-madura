@extends('layouts.app')

@section('title', 'WhatsApp Order')

@section('content')
    <a href="{{ route('penjaga.order.show', $order) }}" class="page-back">
        ← Kembali ke Detail Order
    </a>

    <div class="page-header">
        <h1 class="page-title">📱 Kirim via WhatsApp</h1>
        <p class="page-subtitle">{{ $order->order_number }}</p>
    </div>

    {{-- Message Preview --}}
    <div class="card mb-3">
        <div class="card-header">
            <h3 class="card-title">Preview Pesan</h3>
        </div>
        <div class="card-body">
            <pre
                style="white-space: pre-wrap; font-family: inherit; color: var(--text-secondary); background: var(--bg-dark); padding: 1rem; border-radius: var(--radius-md);">{{ $message }}</pre>
        </div>
    </div>

    {{-- Copy & Send Buttons --}}
    <div class="flex flex-col gap-2">
        <button type="button" class="btn btn-secondary btn-lg btn-block" onclick="copyMessage()">
            📋 Salin Pesan
        </button>

        <a href="https://wa.me/?text={{ $encodedMessage }}" target="_blank" class="btn btn-whatsapp btn-xl btn-block">
            Buka WhatsApp
        </a>
    </div>

    <p class="text-center text-muted mt-3" style="font-size: 0.875rem;">
        Klik "Buka WhatsApp" untuk mengirim pesan ke supplier. <br>
        Anda bisa memilih kontak tujuan di WhatsApp.
    </p>

    @push('scripts')
        <script>
            function copyMessage() {
                const message = @json($message);
                navigator.clipboard.writeText(message).then(function () {
                    alert('Pesan berhasil disalin!');
                }).catch(function () {
                    alert('Gagal menyalin pesan');
                });
            }
        </script>
    @endpush
@endsection