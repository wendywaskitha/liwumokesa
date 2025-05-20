<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket {{ $booking->booking_code }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .ticket {
            border: 2px solid #000;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .details {
            margin-bottom: 20px;
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>E-Ticket Wisata Muna Barat</h1>
            <h2>{{ $package->name }}</h2>
        </div>

        <div class="details">
            <p><strong>Kode Booking:</strong> {{ $booking->booking_code }}</p>
            <p><strong>Nama Pemesan:</strong> {{ $user->name }}</p>
            <p><strong>Tanggal Kunjungan:</strong> {{ $booking->booking_date->format('d M Y') }}</p>
            <p><strong>Jumlah Peserta:</strong> {{ $booking->quantity }} orang</p>
            <p><strong>Total Pembayaran:</strong> Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
        </div>

        <div class="qr-code">
            {!! QrCode::size(150)->generate($booking->booking_code) !!}
        </div>

        <div class="footer">
            <p>Dicetak pada: {{ $generated_at }}</p>
            <p>Tiket ini adalah bukti pembayaran yang sah</p>
        </div>
    </div>
</body>
</html>
