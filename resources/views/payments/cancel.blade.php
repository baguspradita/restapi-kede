<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled</title>
    <style>
        :root {
            --bg: #0f172a;
            --card: #111827;
            --accent: #f59e0b;
            --danger: #ef4444;
            --text: #e5e7eb;
            --muted: #94a3b8;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: radial-gradient(circle at 20% 20%, rgba(239,68,68,0.08), transparent 32%),
                        radial-gradient(circle at 80% 10%, rgba(245,158,11,0.10), transparent 32%),
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
            background: radial-gradient(circle at 30% 20%, rgba(239,68,68,0.12), transparent 40%),
                        radial-gradient(circle at 80% 0%, rgba(245,158,11,0.10), transparent 38%);
        }
        .status {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 12px;
            font-weight: 700;
            letter-spacing: 0.3px;
            background: rgba(239,68,68,0.08);
            color: #fecdd3;
            border: 1px solid rgba(239,68,68,0.35);
        }
        h1 {
            margin: 18px 0 12px;
            font-size: 26px;
            letter-spacing: 0.2px;
        }
        p { margin: 8px 0; color: var(--muted); line-height: 1.6; }
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
            background: var(--danger);
            color: #fff;
            border-radius: 12px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 12px 35px rgba(239,68,68,0.35);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .cta:hover { transform: translateY(-1px); box-shadow: 0 14px 40px rgba(239,68,68,0.4); }
        .cta:active { transform: translateY(0); }
        .info-box {
            margin-top: 16px;
            padding: 12px 14px;
            background: rgba(245,158,11,0.08);
            border: 1px solid rgba(245,158,11,0.25);
            border-radius: 10px;
            color: #fde68a;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="status">
            <span>✕</span>
            <span>Payment Cancelled</span>
        </div>
        <h1>Payment was cancelled</h1>
        <p>{{ $message ?? 'You cancelled the payment process. No charges were made to your account.' }}</p>

        @if($orderNumber)
        <div class="details">
            <div class="row">
                <span class="label">Order Number</span>
                <span class="value">{{ e($orderNumber) }}</span>
            </div>
            @if($customer)
            <div class="row">
                <span class="label">Customer</span>
                <span class="value">{{ e($customer) }}</span>
            </div>
            @endif
            @if($total)
            <div class="row">
                <span class="label">Order Total</span>
                <span class="value">{{ number_format((float) $total, 2) }}</span>
            </div>
            @endif
            <div class="row">
                <span class="label">Payment Status</span>
                <span class="value">Failed • Order Pending</span>
            </div>
        </div>

        <div class="info-box">
            ℹ️ Your order is still pending. You can try paying again from your order history in the app.
        </div>
        @endif

        <a class="cta" href="javascript:window.close();">
            Close this page
        </a>
    </div>
</body>
</html>
