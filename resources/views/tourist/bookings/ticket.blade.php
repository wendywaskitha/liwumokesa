<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket {{ $booking->booking_code }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        /* Header Styles */
        .header {
            background: #1BA0E2;
            color: white;
            padding: 20px;
            position: relative;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .booking-code {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
        }

        /* Content Styles */
        .content {
            padding: 30px;
        }

        .package-info {
            margin-bottom: 30px;
        }

        .package-name {
            font-size: 24px;
            color: #1BA0E2;
            margin: 0 0 10px 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-item {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        .info-label {
            color: #666;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: bold;
        }

        /* QR Code Styles */
        .qr-section {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .qr-code img {
            max-width: 150px;
            height: auto;
        }

        .qr-text {
            font-size: 12px;
            color: #666;
            margin-top: 10px;
        }

        /* Important Notice */
        .important-notice {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 30px;
        }

        .important-notice h4 {
            color: #856404;
            margin: 0 0 10px 0;
            font-size: 14px;
        }

        .important-notice ul {
            margin: 0;
            padding-left: 20px;
            font-size: 12px;
            color: #856404;
        }

        /* Footer Styles */
        .footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            font-size: 12px;
            color: #666;
        }

        .status-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Wisata Muna Barat</div>
            <div class="booking-code">{{ $booking->booking_code }}</div>
            <div class="status-badge">CONFIRMED</div>
        </div>

        <div class="content">
            <div class="package-info">
                <h1 class="package-name">{{ $package->name }}</h1>
                <p style="color: #666;">{{ $package->duration }}</p>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Nama Pemesan</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Tanggal Kunjungan</div>
                    <div class="info-value">{{ $booking->booking_date->format('d M Y') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Jumlah Peserta</div>
                    <div class="info-value">{{ $booking->quantity }} orang</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Total Pembayaran</div>
                    <div class="info-value">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">Status Pembayaran</div>
                    <div class="info-value" style="color: #28a745;">LUNAS</div>
                </div>
            </div>

            <div class="qr-section">
                <div class="qr-code">
                    {!! QrCode::size(150)->generate(route('verify.ticket', $booking->booking_code)) !!}
                </div>
                <div class="qr-text">
                    Scan QR code ini untuk verifikasi tiket
                </div>
            </div>

            <div class="important-notice">
                <h4>Informasi Penting:</h4>
                <ul>
                    <li>Tunjukkan e-ticket ini saat check-in</li>
                    <li>Harap datang 30 menit sebelum waktu kunjungan</li>
                    <li>E-ticket berlaku untuk {{ $booking->quantity }} orang</li>
                    <li>Tiket tidak dapat diuangkan atau dipindahtangankan</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>Tiket ini adalah bukti pembayaran yang sah dan dilindungi oleh Dinas Pariwisata Muna Barat</p>
            <p>Dicetak pada: {{ $generated_at }}</p>
            <p>Untuk bantuan hubungi (0822) 9133-8821 atau email ke info@wisatamunabarat.com</p>
        </div>
    </div>
</body>
</html>
