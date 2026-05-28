<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Campaign</title>
</head>
<body style="margin:0;background:#f4f7f6;font-family:Arial,sans-serif;color:#1f2937;">
    <div style="max-width:640px;margin:0 auto;padding:32px 16px;">
        <div style="background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
            <div style="background:#0f766e;color:#ffffff;padding:24px 28px;">
                <p style="margin:0 0 6px;font-size:13px;letter-spacing:.04em;text-transform:uppercase;">New campaign</p>
                <h1 style="margin:0;font-size:26px;line-height:1.2;">{{ $campaign->name }}</h1>
            </div>

            <div style="padding:28px;">
                <p style="margin:0 0 18px;font-size:16px;line-height:1.6;">
                    We have launched a new fundraising campaign and would love your support.
                </p>

                @if($campaign->description)
                    <p style="margin:0 0 22px;font-size:15px;line-height:1.6;color:#4b5563;">
                        {{ $campaign->description }}
                    </p>
                @endif

                <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-bottom:22px;">
                    <p style="margin:0 0 8px;font-size:14px;">
                        <strong>Goal:</strong>
                        {{ number_format($campaign->goal_amount, 2) }} {{ $campaign->goal_currency }}
                    </p>
                    <p style="margin:0;font-size:14px;">
                        <strong>Type:</strong> {{ ucfirst($campaign->type) }}
                    </p>
                </div>

                <p style="margin:0;font-size:14px;line-height:1.6;color:#6b7280;">
                    Thank you for being part of our donor community.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
