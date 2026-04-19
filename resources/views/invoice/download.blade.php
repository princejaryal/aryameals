<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloading Invoice #{{ $order->id }} - AryaMeals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .download-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 3rem;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .icon-container {
            margin-bottom: 2rem;
        }
        .pdf-icon {
            font-size: 4rem;
            color: #dc3545;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .progress {
            height: 8px;
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-bar {
            background: linear-gradient(90deg, #dc3545, #ff6b6b);
        }
    </style>
</head>
<body>
<div class="download-card">
    <div class="icon-container">
        <i class="fas fa-file-pdf pdf-icon"></i>
    </div>
    <h3 class="mb-3">Downloading Invoice #{{ $order->id }}</h3>
    <p class="text-muted mb-4">
        Your invoice is being prepared for download. You will be redirected to your orders page automatically.
    </p>
    
    <div class="progress mb-4">
        <div class="progress-bar progress-bar-striped progress-bar-animated" 
             role="progressbar" 
             style="width: 100%">
        </div>
    </div>
    
    <div class="d-flex gap-2 justify-content-center">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i>Back to Orders
        </a>
        <button class="btn btn-primary" onclick="window.close()">
            <i class="fas fa-times me-2"></i>Close Window
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-trigger download and redirect
document.addEventListener('DOMContentLoaded', function() {
    // Trigger the PDF download by creating a form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("invoice.download.raw", $order->id) }}';
    form.style.display = 'none';
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    
    // Redirect to orders index after 2 seconds
    setTimeout(function() {
        window.location.href = '{{ route("orders.index") }}';
    }, 2000);
});
</script>
</body>
</html>
