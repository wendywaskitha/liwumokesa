<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>E-Ticket {{ $booking->booking_code }}</title>
    <style>
        @page {
            margin: 0;
            size: A4;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background: #fff;
            color: #2D3748;
        }

        .ticket {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border: 2px solid #E2E8F0;
            border-radius: 16px;
            overflow: hidden;
        }

        .ticket-header {
            background: linear-gradient(135deg, #1a365d 0%, #2B6CB0 100%);
            color: white;
            padding: 20px;
            position: relative;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ticket-body {
            padding: 30px;
            display: flex;
            gap: 30px;
        }

        .ticket-info {
            flex: 1;
        }

        .ticket-aside {
            width: 200px;
            text-align: center;
        }

        .booking-code {
            font-size: 14px;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 50px;
            display: inline-block;
        }

        .package-name {
            font-size: 24px;
            color: #2D3748;
            margin: 0 0 20px 0;
            padding-bottom: 20px;
            border-bottom: 2px dashed #E2E8F0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-label {
            font-size: 12px;
            color: #718096;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            font-weight: bold;
            color: #2D3748;
        }

        .qr-section {
            padding: 20px;
            background: #F7FAFC;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .important-notice {
            background: #FFF5F5;
            border: 1px solid #FED7D7;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .important-notice h4 {
            color: #C53030;
            margin: 0 0 10px 0;
            font-size: 14px;
        }

        .important-notice ul {
            margin: 0;
            padding-left: 20px;
            font-size: 12px;
            color: #2D3748;
        }

        .ticket-footer {
            text-align: center;
            padding: 20px;
            background: #F7FAFC;
            font-size: 12px;
            color: #718096;
            border-top: 2px dashed #E2E8F0;
        }

        .status-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #48BB78;
            color: white;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: -1px;
        }
    </style>
</head>

<body>
    <div class="ticket">
        <div class="ticket-header">
            <div class="header-content">
                <div class="logo">WISATA MUNA BARAT</div>
                <div class="booking-code">{{ $booking->booking_code }}</div>
            </div>
            <div class="status-badge">CONFIRMED</div>
        </div>

        <div class="ticket-body">
            <div class="ticket-info">
                <h1 class="package-name">{{ $package->name }}</h1>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Nama Pemesan</div>
                        <div class="info-value">{{ $user->name }}</div>
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
                </div>

                <div class="important-notice">
                    <h4>Informasi Penting:</h4>
                    <ul>
                        <li>Tunjukkan e-ticket ini saat check-in</li>
                        <li>Harap datang 30 menit sebelum waktu kunjungan</li>
                        <li>E-ticket berlaku untuk {{ $booking->quantity }} orang</li>
                    </ul>
                </div>
            </div>

            <div class="ticket-aside">
                <div class="qr-section">
                    {!! DNS2D::getBarcodeHTML(route('verify.ticket', $booking->booking_code), 'QRCODE', 5, 5) !!}
                    <div class="barcode-text">
                        Scan QR code ini untuk verifikasi tiket<br>
                        <strong>{{ $booking->booking_code }}</strong>
                    </div>
                </div>

            </div>
        </div>

        <div class="ticket-footer">
            <p>Tiket ini adalah bukti pembayaran yang sah</p>
            <p>Dicetak pada: {{ $generated_at }}</p>
        </div>
    </div>
</body>

</html>
