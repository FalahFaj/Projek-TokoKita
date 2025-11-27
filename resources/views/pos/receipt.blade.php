<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->transaction_code }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            max-width: 80mm;
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .item-name {
            flex: 2;
        }
        .item-details {
            flex: 1;
            text-align: right;
        }
        .total-section {
            border-top: 1px dashed #000;
            margin-top: 10px;
            padding-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>TOKO KITA</h2>
        <p>Jl. Jawa 7 no 135, Sumbersari, Jember</p>
        <p>Telp: 087863306466</p>
    </div>

    <div class="transaction-info">
        <p><strong>No: {{ $transaction->transaction_code }}</strong></p>
        <p>Tanggal: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
        <p>Kasir: {{ $transaction->user->name }}</p>
    </div>

    <div class="items">
        @foreach($transaction->detailTransaksi as $item)
        <div class="item">
            <div class="item-name">
                {{ $item->product_name }}<br>
                <small>{{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</small>
            </div>
            <div class="item-details">
                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
            </div>
        </div>
        @endforeach
    </div>

    <div class="total-section">
        <div class="item">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
        </div>
        @if($transaction->discount_amount > 0)
        <div class="item">
            <span>Diskon:</span>
            <span>-Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($transaction->tax_amount > 0)
        <div class="item">
            <span>Pajak:</span>
            <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="item" style="font-weight: bold;">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="item">
            <span>Bayar:</span>
            <span>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
        </div>
        <div class="item">
            <span>Kembali:</span>
            <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
        </div>
        <div class="item">
            <span>Metode:</span>
            <span>{{ strtoupper($transaction->payment_method) }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih atas kunjungan Anda</p>
        <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="btn btn-primary">Cetak Struk</button>
        <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
