<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <style>
        :root {
            --bg: #0f172a;
            --card: #111827;
            --accent: #22c55e;
            --text: #e5e7eb;
            --muted: #94a3b8;
            --danger: #ef4444;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: radial-gradient(circle at 20% 20%, rgba(34,197,94,0.08), transparent 32%),
                        radial-gradient(circle at 80% 10%, rgba(34,197,94,0.10), transparent 32%),
                        var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            width: 100%;
            max-width: 520px;
            background: linear-gradient(145deg, rgba(255,255,255,0.02), rgba(255,255,255,0.00));
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.35);
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }
        .card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 18px;
            pointer-events: none;
            background: radial-gradient(circle at 30% 20%, rgba(34,197,94,0.15), transparent 40%),
                        radial-gradient(circle at 80% 0%, rgba(34,197,94,0.12), transparent 38%);
        }
        .status {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
            background: rgba(34,197,94,0.1);
            color: #bbf7d0;
            border: 1px solid rgba(34,197,94,0.35);
        }
        .status.error {
            background: rgba(239,68,68,0.08);
            color: #fecdd3;
            border-color: rgba(239,68,68,0.35);
        }
        h1 {
            margin: 18px 0 12px;
            font-size: 26px;
            letter-spacing: 0.2px;
        }
        p { margin: 4px 0; color: var(--muted); }
        .details {
            margin-top: 18px;
            padding: 16px;
            border-radius: 14px;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.04);
        }
        .row { display: flex; justify-content: space-between; margin: 8px 0; }
        .label { color: var(--muted); font-size: 14px; }
        .value { color: var(--text); font-weight: 600; }
        .cta {
            margin-top: 22px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            background: var(--accent);
            color: #052e16;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 12px 35px rgba(34,197,94,0.35);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .cta:hover { transform: translateY(-1px); box-shadow: 0 14px 40px rgba(34,197,94,0.4); }
        .cta:active { transform: translateY(0); }
    </style>
</head>
<body>
    <div class="card">
        @if($success)
            <div class="status">
                <span>✓</span>
                <span>Payment Successful</span>
            </div>
            <h1>Thank you{{ $customer ? ', ' . e($customer) : '' }}!</h1>
            <p>Your payment has been confirmed. You can safely close this page.</p>

            <div class="details">
                <div class="row">
                    <span class="label">Order Number</span>
                    <span class="value">{{ e($orderNumber) }}</span>
                </div>
                @if($total)
                <div class="row">
                    <span class="label">Total Paid</span>
                    <span class="value">{{ number_format((float) $total, 2) }}</span>
                </div>
                @endif
                <div class="row">
                    <span class="label">Status</span>
                    <span class="value">Paid • Processing</span>
                </div>
            </div>

            <a class="cta" href="javascript:window.close();">
                Close this page
            </a>
        @else
            <div class="status error">
                <span>!</span>
                <span>Could not confirm payment</span>
            </div>
            <h1>We hit a snag.</h1>
            <p>{{ $message }}</p>
            @if($orderNumber)
            <div class="details">
                <div class="row">
                    <span class="label">Order Number</span>
                    <span class="value">{{ e($orderNumber) }}</span>
                </div>
            </div>
            @endif
            <a class="cta" style="background: var(--danger); color: #fff; box-shadow: 0 12px 35px rgba(239,68,68,0.35);" href="javascript:window.close();">
                Close and retry
            </a>
        @endif
    </div>
</body>
</html>
