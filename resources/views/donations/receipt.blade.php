<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Donation Receipt #{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }} - Donation Module</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f5f5f5;
            color: #1a1a2e;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 20px;
        }
        .receipt-wrapper {
            background: #fff;
            width: 100%;
            max-width: 600px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            overflow: hidden;
        }
        .receipt-header {
            background: #1a2035;
            color: #fff;
            padding: 32px 40px 24px;
        }
        .receipt-header .org-name {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: #5eead4;
        }
        .receipt-header .receipt-title {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .receipt-header .receipt-number {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            margin-top: 12px;
        }
        .receipt-body {
            padding: 32px 40px;
        }
        .receipt-section {
            margin-bottom: 28px;
        }
        .receipt-section h3 {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #94a3b8;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f8fafc;
        }
        .receipt-row:last-child { border-bottom: none; }
        .receipt-row .label {
            font-size: 13px;
            color: #64748b;
        }
        .receipt-row .value {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
            text-align: right;
        }
        .amount-highlight {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
        }
        .amount-highlight .amount-label {
            font-size: 13px;
            color: #166534;
            font-weight: 600;
        }
        .amount-highlight .amount-value {
            font-size: 26px;
            font-weight: 800;
            color: #15803d;
        }
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }
        .badge-completed { background: #dcfce7; color: #166534; }
        .badge-pending   { background: #fef9c3; color: #854d0e; }
        .badge-failed    { background: #fee2e2; color: #991b1b; }
        .badge-refunded  { background: #f1f5f9; color: #475569; }
        .badge-yes { background: #dcfce7; color: #166534; }
        .badge-no  { background: #f1f5f9; color: #64748b; }
        .receipt-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 20px 40px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
        .print-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 24px auto 0;
            padding: 10px 24px;
            background: #14b8a6;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        .print-btn:hover { background: #0d9488; }
        .print-btn-wrapper {
            text-align: center;
            padding: 0 40px 28px;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .receipt-wrapper {
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }
            .print-btn-wrapper { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt-wrapper">
        <!-- Header -->
        <div class="receipt-header">
            <div class="org-name">Donation Module</div>
            <div class="receipt-title">Official Donation Receipt</div>
            <div class="receipt-number">#{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- Body -->
        <div class="receipt-body">

            <!-- Amount Highlight -->
            <div class="amount-highlight">
                <span class="amount-label">Amount Donated</span>
                <span class="amount-value">{{ $donation->currency }} {{ number_format($donation->amount_original, 2) }}</span>
            </div>

            <!-- Donor Info -->
            <div class="receipt-section">
                <h3>Donor Information</h3>
                @if($donation->donor)
                <div class="receipt-row">
                    <span class="label">Name</span>
                    <span class="value">{{ $donation->donor->first_name }} {{ $donation->donor->last_name }}</span>
                </div>
                @if($donation->donor->email)
                <div class="receipt-row">
                    <span class="label">Email</span>
                    <span class="value">{{ $donation->donor->email }}</span>
                </div>
                @endif
                @if($donation->donor->organization_name)
                <div class="receipt-row">
                    <span class="label">Organization</span>
                    <span class="value">{{ $donation->donor->organization_name }}</span>
                </div>
                @endif
                @else
                <div class="receipt-row">
                    <span class="label">Donor</span>
                    <span class="value">—</span>
                </div>
                @endif
            </div>

            <!-- Donation Details -->
            <div class="receipt-section">
                <h3>Donation Details</h3>
                <div class="receipt-row">
                    <span class="label">Campaign</span>
                    <span class="value">{{ $donation->campaign?->name ?? 'General Fund' }}</span>
                </div>
                <div class="receipt-row">
                    <span class="label">Currency</span>
                    <span class="value">{{ $donation->currency }}</span>
                </div>
                @if($donation->currency !== 'USD')
                <div class="receipt-row">
                    <span class="label">Amount (USD)</span>
                    <span class="value">${{ number_format($donation->amount_base, 2) }}</span>
                </div>
                @endif
                <div class="receipt-row">
                    <span class="label">Payment Status</span>
                    <span class="value">
                        <span class="badge badge-{{ $donation->payment_status }}">
                            {{ ucfirst($donation->payment_status) }}
                        </span>
                    </span>
                </div>
                <div class="receipt-row">
                    <span class="label">Gift Aid Eligible</span>
                    <span class="value">
                        <span class="badge {{ $donation->gift_aid_eligible ? 'badge-yes' : 'badge-no' }}">
                            {{ $donation->gift_aid_eligible ? 'Yes' : 'No' }}
                        </span>
                    </span>
                </div>
                @if($donation->transaction_reference)
                <div class="receipt-row">
                    <span class="label">Transaction Ref.</span>
                    <span class="value" style="font-family: monospace;">{{ $donation->transaction_reference }}</span>
                </div>
                @endif
                <div class="receipt-row">
                    <span class="label">Date</span>
                    <span class="value">{{ $donation->donated_at?->format('d F Y') ?? $donation->created_at->format('d F Y') }}</span>
                </div>
            </div>

        </div>

        <!-- Print Button -->
        <div class="print-btn-wrapper">
            <button class="print-btn" onclick="window.print()">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Receipt
            </button>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p>Thank you for your generous contribution.</p>
            <p style="margin-top: 4px;">This receipt was generated on {{ now()->format('d F Y, H:i') }}.</p>
        </div>
    </div>
</body>
</html>
