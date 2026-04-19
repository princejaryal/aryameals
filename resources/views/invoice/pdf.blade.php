<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            background: #fff;
            font-size: 12px;
        }
        .invoice {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 15px;
            background: #fff;
        }
        .header {
            border-bottom: 2px solid #ff6b35;
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 22px;
            font-weight: bold;
            color: #ff6b35;
            margin-bottom: 8px;
        }
        .logo-header {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .header-info {
            text-align: right;
            font-size: 11px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-details h2 {
            color: #333;
            margin: 0;
            font-size: 18px;
        }
        .invoice-date {
            color: #666;
            margin: 3px 0;
            font-size: 11px;
        }
        .billing-info {
            margin-bottom: 20px;
        }
        .billing-info h3 {
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            font-size: 14px;
        }
        .billing-info div {
            font-size: 11px;
            margin: 2px 0;
        }
        .order-items {
            margin-bottom: 20px;
        }
        .order-items h3 {
            color: #333;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 6px 8px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        th {
            background: #f8f8f8;
            font-weight: bold;
            font-size: 11px;
        }
        .total-section {
            text-align: right;
            margin-top: 15px;
        }
        .total-row {
            margin: 3px 0;
            font-size: 11px;
        }
        .grand-total {
            font-size: 14px;
            font-weight: bold;
            color: #ff6b35;
            border-top: 2px solid #ff6b35;
            padding-top: 8px;
            margin-top: 8px;
        }
        .footer {
            text-align: center;
            margin-top: 25px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 10px;
        }
        .footer div {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <div class="logo-header">
                <div class="logo">AryaMeals</div>
            </div>
            <div class="header-info">
                <div>Fresh & Delicious Food Delivered to Your Doorstep</div>
                <div>Chamba, Himachal Pradesh</div>
            </div>
        </div>

        <div class="invoice-info">
            <div class="invoice-details">
                <h2>INVOICE</h2>
                <div class="invoice-date">Invoice #{{ $order->id }}</div>
                <div class="invoice-date">Date: {{ $order->created_at->format('d M Y') }}</div>
            </div>
            <div class="order-status">
                <h3>Order Status</h3>
                <div>Payment: Cash on Delivery</div>
                <div>Amount: Rs. {{ number_format($order->total_amount, 0) }}</div>
            </div>
        </div>

        <div class="billing-info">
            <h3>Billing Information</h3>
            <div><strong>Customer:</strong> {{ $order->customer_name }}</div>
            <div><strong>Phone:</strong> {{ $order->customer_phone }}</div>
            <div><strong>Delivery Address:</strong> {{ $order->customer_address }}</div>
        </div>

        <div class="order-items">
            <h3>Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Restaurant</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td>{{ $item->menuItem->name ?? $item->name ?? 'Item Deleted' }}</td>
                            <td>{{ $item->menuItem->restaurant->name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rs. {{ number_format($item->price, 0) }}</td>
                            <td>Rs. {{ number_format($item->price * $item->quantity, 0) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <div class="total-row">Subtotal: Rs. {{ number_format($order->orderItems->sum(function($item) { return $item->price * $item->quantity; }), 0) }}</div>
            <div class="total-row">Delivery Fee: Rs. 49</div>
            <div class="total-row">Platform Fee: Rs. {{ number_format($order->total_amount - $order->orderItems->sum(function($item) { return $item->price * $item->quantity; }) - 49, 0) }}</div>
            <div class="grand-total">Grand Total: Rs. {{ number_format($order->total_amount, 0) }}</div>
        </div>

        <div class="footer">
            <div>Thank you for ordering from AryaMeals!</div>
            <div>Contact: +918544772623 | support@aryameals.com</div>
            <div>www.aryameals.com</div>
        </div>
    </div>
</body>
</html>
