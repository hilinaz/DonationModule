<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank You for Your Donation</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f1f5f9; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: #1a2035; padding: 36px 40px; text-align: center; }
        .header .logo { font-size: 20px; font-weight: 700; color: #5eead4; letter-spacing: -0.5px; }
        .header .tagline { font-size: 12px; color: #94a3b8; margin-top: 4px; text-transform: uppercase; letter-spacing: 1px; }
        .hero { background: linear-gradient(135deg, #0d9488, #14b8a6); padding: 40px; text-align: center; }
        .hero .icon { font-size: 48px; margin-bottom: 12px; }
        .hero h1 { font-size: 26px; font-weight: 800; color: #fff; margin-bottom: 8px; }
        .hero p { font-size: 15px; color: #ccfbf1; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 16px; color: #334155; margin-bottom: 20px; }
        .amount-box { background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 10px; padding: 20px 24px; margin: 24px 0; display: flex; justify-content: space-between; align-items: center; }
        .amount-box .label { font-size: 13px; color: #166534; font-weight: 600; }
        .amount-box .value { font-size: 28px; font-weight: 800; color: #15803d; }
        .details { background: #f8fafc; border-radius: 10px; padding: 20px 24px; margin: 20px 0; }
        .details h3 { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 14px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-row .key { color: #64748b; }
        .detail-row .val { font-weight: 600; color: #1e293b; }
        .badge { display: inline-block; padding: 2px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; background: #dcfce7; color: #166534; }
        .cta { text-align: center; margin: 28px 0; }
        .cta a { display: inline-block; padding: 12px 28px; background: #14b8a6; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 14px; }
        .message { font-size: 14px; color: #475569; line-height: 1.7; margin: 20px 0; }
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 40px; text-align: center; font-size: 12px; color: #94a3b8; }
        .footer a { color: #14b8a6; text-decoration: none; }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="logo">Donation Module</div>
            <div class="tagline">Empowering Real Change</div>
        </div>

        <!-- Hero -->
        <div class="hero">
            <div class="icon">💚</div>
            <h1>Thank You!</h1>
            <p>Your generosity makes a real difference.</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">
                Dear <strong>{{ $donation->donor->first_name ?? 'Valued Donor' }}</strong>,
            </p>

            <p class="message">
                We are deeply grateful for your generous contribution.
                Your donation helps us continue our mission and create lasting impact in the communities we serve.
            </p>

            <!-- Amount highlight -->
            <div class="amount-box">
                <span class="label">Donation Amount</span>
                <span class="value">{{ $donation->currency }} {{ number_format($donation->amount_original, 2) }}</span>
            </div>

            <!-- Donation details -->
            <div class="details">
                <h3>Donation Details</h3>
                <div class="detail-row">
                    <span class="key">Receipt No.</span>
                    <span class="val">#{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="detail-row">
                    <span class="key">Campaign</span>
                    <span class="val">{{ $donation->campaign?->name ?? 'General Fund' }}</span>
                </div>
                <div class="detail-row">
                    <span class="key">Donation Type</span>
                    <span class="val">{{ ucfirst(str_replace('_', ' ', $donation->donation_type)) }}</span>
                </div>
                @if($donation->currency !== 'USD')
                <div class="detail-row">
                    <span class="key">Amount (USD)</span>
                    <span class="val">${{ number_format($donation->amount_base, 2) }}</span>
                </div>
                @endif
                <div class="detail-row">
                    <span class="key">Status</span>
                    <span class="val"><span class="badge">{{ ucfirst($donation->payment_status) }}</span></span>
                </div>
                <div class="detail-row">
                    <span class="key">Gift Aid</span>
                    <span class="val">{{ $donation->gift_aid_eligible ? 'Yes — eligible' : 'No' }}</span>
                </div>
                <div class="detail-row">
                    <span class="key">Date</span>
                    <span class="val">{{ ($donation->donated_at ?? $donation->created_at)->format('d F Y') }}</span>
                </div>
            </div>

            <p class="message">
                This email serves as your official donation confirmation. Please keep it for your records.
                @if($donation->gift_aid_eligible)
                    As a Gift Aid eligible donation, we will claim an additional 25% from HMRC on your behalf.
                @endif
            </p>

            <!-- CTA -->
            <div class="cta">
                <a href="{{ config('app.url') }}/portal">View Your Donation History</a>
            </div>

            <p class="message" style="font-size: 13px; color: #94a3b8;">
                If you have any questions about your donation, please don't hesitate to contact us.
                We are here to help and deeply appreciate your continued support.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Donation Module</strong> &mdash; Empowering Real Change</p>
            <p style="margin-top: 6px;">&copy; {{ date('Y') }} Donation Management Platform. All rights reserved.</p>
            <p style="margin-top: 6px;">
                You received this email because you made a donation through our platform.
            </p>
        </div>
    </div>
</body>
</html>
