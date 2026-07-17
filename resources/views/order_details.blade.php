<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details #{{ $order->order_code }}</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; }
        .receipt-card { background: #fff; color: #1e293b; }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-md flex justify-between mb-4 no-print">
        <a href="/history-order" class="text-xs font-bold text-slate-400 hover:text-white transition">← Kembali</a>
        <button onclick="downloadReceipt()" class="text-xs font-bold bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition cursor-pointer border-0">
            Download Bukti Sewa
        </button>
    </div>

    <div id="receiptArea" class="w-full max-w-md receipt-card rounded-[2.5rem] overflow-hidden shadow-2xl relative">
        <div class="h-24 bg-slate-100 flex justify-center relative">
            <div class="absolute -bottom-10 w-20 h-20 rounded-full border-4 border-white overflow-hidden bg-slate-900 flex items-center justify-center">
                 <span class="text-white font-black text-2xl">{{ substr($order->item_name, 0, 1) }}</span>
            </div>
            <span class="absolute top-4 left-10 opacity-30">📦</span>
            <span class="absolute top-10 right-12 opacity-30">⭐</span>
        </div>

        <div class="pt-14 pb-10 px-8 text-center">
            <h2 class="text-lg font-extrabold text-slate-900">Order details</h2>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">ID: {{ $order->order_code }}</p>

            <div class="mt-6 space-y-4 text-left">
                <div class="pb-4 border-b border-dashed border-slate-200">
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Item Sewa</p>
                    <p class="text-sm font-bold text-slate-800">1 x {{ $order->item_name }}</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Tanggal Transaksi</p>
                        <p class="text-xs font-bold text-slate-800">{{ date('d M Y', strtotime($order->created_at)) }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Customer</p>
                        <p class="text-xs font-bold text-slate-800">{{ $order->customer_name }}</p>
                    </div>
                </div>

                <div>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Alamat Email</p>
                    <p class="text-[12px] font-bold text-slate-700">{{ $order->user_email }}</p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-xs text-slate-500 font-medium">Status Pembayaran</span>
                    
                    @if($order->status === 'approved' || $order->status === 'expired')
                        <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide">Success</span>
                    @elseif($order->status === 'declined')
                        <span class="bg-rose-100 text-rose-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide">Declined</span>
                    @else
                        <span class="bg-amber-100 text-amber-600 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide">Pending</span>
                    @endif
                </div>

                @php
                    $cleanPrice = (int) preg_replace('/[^0-9]/', '', $order->item_price);
                    $totalPayment = $cleanPrice * $order->duration;
                @endphp
                <div class="bg-slate-50 rounded-2xl p-4 flex justify-between items-center">
                    <span class="text-sm font-bold text-slate-900">Total Paid</span>
                    <span class="text-lg font-black text-blue-600">Rp {{ number_format($totalPayment, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-8">
                <p class="text-[10px] text-slate-400 italic">Terima kasih telah menggunakan layanan {{ $configs['app_name'] ?? 'Day-Rent' }}. Harap simpan bukti ini untuk pengambilan barang.</p>
            </div>
        </div>
    </div>

    <script>
        function downloadReceipt() {
            const area = document.getElementById('receiptArea');
            html2canvas(area, {
                backgroundColor: '#0f172a',
                scale: 3, // Menghasilkan kualitas gambar PNG yang sangat tajam
                borderRadius: 40
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'Receipt-{{ $order->order_code }}.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>
</body>
</html>