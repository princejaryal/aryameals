@extends('layouts.app')

@section('title', 'Downloading Invoice - AryaMeals')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="fas fa-file-pdf fa-4x text-primary"></i>
                    </div>
                    <h3 class="card-title mb-3">Downloading Invoice #{{ $order->id }}</h3>
                    <p class="card-text text-muted mb-4">
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
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
@endpush
