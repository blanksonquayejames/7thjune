<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Receipt</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 20px; }
        .logo { max-width: 150px; height: auto; margin-bottom: 15px; }
        .title { color: #2563eb; font-size: 24px; font-weight: bold; margin: 0; text-transform: uppercase; }
        .order-meta { font-size: 14px; color: #64748b; margin-bottom: 30px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th { background-color: #f1f5f9; padding: 12px; text-align: left; font-size: 14px; color: #475569; }
        .table td { padding: 12px; border-bottom: 1px solid #e2e8f0; }
        .item-row { font-size: 15px; }
        .totals { float: right; width: 60%; margin-bottom: 40px; }
        .total-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .total-row.grand-total { font-weight: bold; font-size: 18px; color: #2563eb; border-bottom: none; border-top: 2px solid #e2e8f0; padding-top: 15px; margin-top: 5px; }
        .footer { text-align: center; font-size: 13px; color: #94a3b8; clear: both; padding-top: 20px; border-top: 1px solid #e2e8f0; }
        .clearfix::after { content: ""; display: table; clear: both; }
        .btn { display: inline-block; background-color: #2563eb; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <!-- Ensure APP_URL is correctly set in .env -->
            <h1 class="title">7th June Computers</h1>
            <p style="margin: 5px 0; color: #64748b;">Thanks for shopping with us!</p>
        </div>

        <div class="order-meta">
            <p><strong>Hi {{ $order->user->name }},</strong></p>
            <p>We've received your payment. Your order is now being processed.</p>
            <table style="width:100%; margin-top: 20px;">
                <tr>
                    <td style="vertical-align: top; width: 50%;">
                        <strong>Order details:</strong><br>
                        Order ID: #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}<br>
                        Date: {{ $order->created_at->format('F d, Y') }}<br>
                        Status: <span style="color:#10b981; font-weight:bold;">Processing</span>
                    </td>
                    <td style="vertical-align: top; width: 50%;">
                        <strong>Shipping details:</strong><br>
                        {{ $order->phone }}<br>
                        {{ $order->shipping_address }}
                    </td>
                </tr>
            </table>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th style="text-align: right;">Price</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr class="item-row">
                    <td>{{ $item->product ? $item->product->name : 'Unknown Product' }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">₵{{ number_format($item->price, 2) }}</td>
                    <td style="text-align: right;">₵{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>₵{{ number_format($order->total, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Tax (7.5%):</span>
                <span>₵{{ number_format($order->total * 0.075, 2) }}</span>
            </div>
            <div class="total-row grand-total">
                <span>Grand Total:</span>
                <span>₵{{ number_format($order->total + ($order->total * 0.075), 2) }}</span>
            </div>
        </div>
        
        <div class="clearfix"></div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/orders') }}" class="btn">View Order History</a>
        </div>

        <div class="footer">
            <p>If you have any questions about your order, please hit reply to contact us at june7thcomputers@gmail.com.</p>
            <p>&copy; {{ date('Y') }} 7th June Computers. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
