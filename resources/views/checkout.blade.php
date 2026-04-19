@extends('layouts.app')

@section('title', 'Secure Checkout - AryaMeals')

@section('content')
<!-- Modern Checkout Section -->
<section class="checkout-section py-4" style="margin-top:80px">
    <div class="container-fluid">

        <div class="row">
            <!-- Main Content - Full Width -->
            <div class="col-12">
                <!-- Order Summary Card -->
                <div class="checkout-card mb-4">
                    <div class="card-header-custom">
                        <div class="header-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h5 class="mb-0 fw-semibold">Order Summary</h5>
                    </div>
                    <div class="card-body-custom">
                        @if($cartItems->count() > 0)
                        <div class="order-items mb-3">
                            @foreach($cartItems as $item)
                            <div class="order-item d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div>
                                    <strong>{{ $item->menuItem->name }}</strong>
                                    <div class="small text-muted">
                                        {{ $item->portion_size == 'half' ? 'Half Plate' : 'Full Plate' }} × {{ $item->quantity }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <strong>Rs. {{ number_format($item->total_price, 0) }}</strong>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Fee Breakdown -->
                        @if(isset($feeData['fees']) && !empty($feeData['fees']))
                        <div class="fee-breakdown mb-3">
                            @foreach($feeData['fees'] as $fee)
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted">{{ $fee['name'] }}</span>
                                <span class="small">Rs. {{ $fee['amount'] }}</span>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Total -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <h5 class="mb-0 text-dark">Total Amount</h5>
                            <h4 class="mb-0 text-danger fw-bold cart-total-amount">
                                {{ isset($feeData['grand_total']) ? $feeData['grand_total'] : 'Rs. 0' }}
                            </h4>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Your cart is empty</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Delivery Address Card -->
                <div class="checkout-card mb-4">
                    <div class="card-header-custom">
                        <div class="header-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h5 class="mb-0 fw-semibold">Delivery Address</h5>
                        <span class="badge-required">Required</span>
                    </div>

                    <div class="card-body-custom">
                        <!-- Address Selection State -->
                        <div id="addressSelectionArea">
                            <!-- Loading State -->
                            <div id="addressLoading" class="text-center py-4 d-none">
                                <div class="spinner-border" role="status" style="color:#f97316">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="text-muted mt-2">Loading your addresses...</p>
                            </div>

                            <!-- No Address State -->
                            <div id="noAddressState" class="text-center py-5">
                                <div class="empty-state-icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <h6 class="mt-3 fw-semibold">No Delivery Address</h6>
                                <p class="text-muted small">Add a delivery address to continue</p>
                                <button type="button" class="btn btn-primary btn-sm px-4" onclick="openAddressModal()">
                                    <i class="fas fa-plus-circle me-2"></i>Add New Address
                                </button>
                            </div>

                            <!-- Address List State -->
                            <div id="addressListState" class="d-none">
                                <div class="address-list" id="addressListContainer"></div>
                                <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="openAddressModal()">
                                    <i class="fas fa-plus me-2"></i>Add New Address
                                </button>
                            </div>

                            <!-- Selected Address Display -->
                            <div id="selectedAddressDisplay" class="d-none">
                                <div class="selected-address-card">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="selected-badge">
                                            <i class="fas fa-check-circle"></i> Selected
                                        </div>
                                        <button type="button" class="btn-change-address" onclick="changeAddress()">
                                            <i class="fas fa-edit"></i> Change
                                        </button>
                                    </div>
                                    <div id="selectedAddressContent"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COD Order Confirmation Section (Hidden by default) -->
        <div id="codConfirmationSection" class="d-none mt-4">
            <div class="checkout-card">
                <div class="card-header-custom">
                    <div class="header-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h5 class="mb-0 fw-semibold">Confirm Cash on Delivery Order</h5>
                </div>
                <div class="card-body-custom">
                    <div class="text-center mb-4">
                        <i class="fas fa-hand-holding-usd" style="font-size: 48px; color: #28a745;"></i>
                        <h5 class="mt-3">Cash on Delivery</h5>
                        <p class="text-muted">Please confirm your order details</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Order Summary</h6>
                            <div class="payment-details-popup">
                                <div class="detail-row">
                                    <span>Payment Method:</span>
                                    <strong>Cash on Delivery</strong>
                                </div>
                                <div class="detail-row">
                                    <span>Total Amount:</span>
                                    <strong id="codTotalAmountInline">₹0</strong>
                                </div>
                                <div class="detail-row">
                                    <span>Delivery Address:</span>
                                    <strong id="codAddressInline">-</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Payment Instructions:</strong><br>
                                • Pay the exact amount when delivery arrives<br>
                                • Keep cash ready for smooth delivery<br>
                                • Delivery person will provide receipt
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-place-order btn-lg" onclick="confirmCodOrderInline()">
                            <i class="fas fa-shopping-cart me-2"></i>Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Place Order Button (shown only for online payment) -->
        <div class="text-center mt-4" id="placeOrderBtnSection">
            <button type="button" class="btn btn-place-order btn-lg" onclick="submitOrder()">
                <i class="fas fa-shopping-cart me-2"></i>
                Place Order
            </button>
            <p class="terms-text mt-2">
                By placing this order, you agree to our Terms & Conditions
            </p>
        </div>

        <!-- Hidden Form for Submission -->
        <form id="checkoutForm" method="POST" action="{{ route('checkout.process') }}" class="d-none">
            @csrf
            <input type="hidden" name="address_id" id="selectedAddressId">
            <input type="hidden" name="payment_method" id="paymentMethod" value="cod">
            <input type="hidden" name="special_instructions" id="specialInstructionsHidden">
            <input type="hidden" name="card_number" id="cardNumberHidden">
            <input type="hidden" name="card_name" id="cardNameHidden">
            <input type="hidden" name="card_expiry" id="cardExpiryHidden">
            <input type="hidden" name="card_cvv" id="cardCvvHidden">
            <input type="hidden" name="payment_type" id="paymentTypeHidden" value="cod">
        </form>
    </div>
</section>

<!-- Address Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>
                    Add Delivery Address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addressForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-sm">Address Label *</label>
                            <input type="text" class="form-control-modern" id="addrLabel" name="name" placeholder="Home, Office, etc." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-sm">Phone Number</label>
                            <input type="tel" class="form-control-modern" id="addrPhone" name="phone" placeholder="Contact number">
                        </div>
                        <div class="col-12">
                            <label class="form-label-sm">Address Line 1 *</label>
                            <input type="text" class="form-control-modern" id="addrLine1" name="address_line_1" placeholder="Street, House No." required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-sm">Address Line 2</label>
                            <input type="text" class="form-control-modern" id="addrLine2" name="address_line_2" placeholder="Landmark (optional)">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-sm">City *</label>
                            <input type="text" class="form-control-modern" id="addrCity" name="city" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-sm">State *</label>
                            <input type="text" class="form-control-modern" id="addrState" name="state" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-sm">PIN Code *</label>
                            <input type="text" class="form-control-modern" id="addrPostal" name="postal_code" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="addrDefault" name="is_default">
                                <label class="form-check-label" for="addrDefault">
                                    Set as default address
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveAddress()">Save Address</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Checkout Page Professional Styles */
    .checkout-section {
        background: #f7f9fc;
        min-height: calc(100vh - 200px);
        padding: 2rem 0 4rem;
    }

    /* Cards */
    .checkout-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 10px 25px -5px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        transition: box-shadow 0.2s;
    }

    .checkout-card:hover {
        box-shadow: 0 10px 40px -12px rgba(0, 0, 0, 0.1);
    }

    .card-header-custom {
        padding: 1rem 1.5rem;
        background: white;
        border-bottom: 1px solid #f0f2f5;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-icon {
        width: 36px;
        height: 36px;
        background: #fff7ed;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f97316;
    }

    .badge-required,
    .badge-optional {
        font-size: 0.65rem;
        padding: 0.25rem 0.6rem;
        border-radius: 30px;
        margin-left: auto;
    }

    .badge-required {
        background: #fef2f2;
        color: #dc2626;
    }

    .badge-optional {
        background: #f0fdf4;
        color: #16a34a;
    }

    .card-body-custom {
        padding: 1.5rem;
    }

    /* Empty State */
    .empty-state-icon {
        width: 80px;
        height: 80px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 2rem;
        color: #94a3b8;
    }

    /* Address List */
    .address-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .address-item {
        border: 1.5px solid #eef2f6;
        border-radius: 16px;
        padding: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .address-item:hover {
        border-color: #f97316;
        background: #fffaf5;
    }

    .address-item.selected {
        border-color: #f97316;
        background: #fff7ed;
    }

    .selected-address-card {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 16px;
        padding: 1rem;
    }

    .selected-badge {
        font-size: 0.7rem;
        font-weight: 600;
        color: #15803d;
        background: #dcfce7;
        padding: 0.25rem 0.7rem;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-change-address {
        background: none;
        border: none;
        color: #f97316;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
    }

    /* Form Controls */
    .form-control-modern {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid #eef2f6;
        border-radius: 12px;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .form-control-modern:focus {
        outline: none;
        border-color: #f97316;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
    }

    .form-label-sm {
        font-size: 0.75rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.35rem;
        display: block;
    }

    /* Place Order Button */
    .btn-place-order {
        width: 100%;
        max-width: 400px;
        background: linear-gradient(135deg, #f97316, #ea580c);
        border: none;
        border-radius: 14px;
        padding: 0.9rem;
        color: white;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.2s;
    }

    .btn-place-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(249, 115, 22, 0.3);
    }

    .btn-place-order:disabled {
        opacity: 0.7;
        transform: none;
    }

    .terms-text {
        font-size: 0.7rem;
        color: #94a3b8;
        text-align: center;
        margin-top: 1rem;
    }

    /* Notification */
    .checkout-notification {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 9999;
        padding: 1rem 1.5rem;
        border-radius: 16px;
        background: white;
        box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transform: translateX(120%);
        transition: transform 0.3s ease;
    }

    .checkout-notification.show {
        transform: translateX(0);
    }

    .checkout-notification.success {
        border-left: 4px solid #22c55e;
    }

    .checkout-notification.error {
        border-left: 4px solid #ef4444;
    }

    /* Detail Row */
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #eef2f6;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    /* Alert */
    .alert-info {
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        color: #0369a1;
        border-radius: 12px;
    }

    /* Modal */
    .modal-content {
        border-radius: 20px;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid #f0f2f5;
        padding: 1.25rem 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #f0f2f5;
        padding: 1rem 1.5rem;
    }
</style>

<script>
    // ============================================
    // CHECKOUT STATE MANAGEMENT
    // ============================================

    let selectedAddressObj = null;
    let userAddresses = [];
    let addressModalInstance = null;
    let isInitializing = true;

    // ============================================
    // INITIALIZATION
    // ============================================

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modal
        addressModalInstance = new bootstrap.Modal(document.getElementById('addressModal'));

        // Set default payment method
        document.getElementById('paymentMethod').value = 'cod';

        // Load addresses
        loadUserAddresses();

        isInitializing = false;
    });

    // ============================================
    // ADDRESS MANAGEMENT
    // ============================================

    function loadUserAddresses() {
        const loadingDiv = document.getElementById('addressLoading');
        const noAddressDiv = document.getElementById('noAddressState');
        const addressListDiv = document.getElementById('addressListState');

        loadingDiv.classList.remove('d-none');
        noAddressDiv.classList.add('d-none');
        addressListDiv.classList.add('d-none');

        fetch('{{ route("addresses.index") }}')
            .then(res => res.json())
            .then(result => {
                loadingDiv.classList.add('d-none');

                if (result.success && result.addresses && result.addresses.length > 0) {
                    userAddresses = result.addresses;
                    displayAddressList();
                    addressListDiv.classList.remove('d-none');

                    // Auto-select default address or first address
                    const defaultAddr = userAddresses.find(a => a.is_default) || userAddresses[0];
                    selectAddress(defaultAddr);
                } else {
                    noAddressDiv.classList.remove('d-none');
                }
            })
            .catch(err => {
                loadingDiv.classList.add('d-none');
                noAddressDiv.classList.remove('d-none');
                showNotification('Failed to load addresses', 'error');
            });
    }

    function displayAddressList() {
        const container = document.getElementById('addressListContainer');
        container.innerHTML = userAddresses.map(addr => `
            <div class="address-item" data-id="${addr.id}" onclick="selectAddressById(${addr.id})">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>${escapeHtml(addr.name)}</strong>
                        ${addr.is_default ? '<span class="badge bg-warning text-dark ms-2">Default</span>' : ''}
                        <p class="small text-muted mt-1 mb-0">
                            ${escapeHtml(addr.address_line_1)}${addr.address_line_2 ? ', ' + escapeHtml(addr.address_line_2) : ''}<br>
                            ${escapeHtml(addr.city)}, ${escapeHtml(addr.state)} - ${escapeHtml(addr.postal_code)}
                        </p>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function selectAddressById(id) {
        const address = userAddresses.find(a => a.id == id);
        if (address) selectAddress(address);
    }

    function selectAddress(address) {
        selectedAddressObj = address;
        document.getElementById('selectedAddressId').value = address.id;

        // Show selected display
        document.getElementById('addressListState').classList.add('d-none');
        document.getElementById('selectedAddressDisplay').classList.remove('d-none');

        const selectedContent = document.getElementById('selectedAddressContent');
        selectedContent.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong>${escapeHtml(address.name)}</strong>
                    <div class="text-muted small">
                        ${escapeHtml(address.address_line_1)}${address.address_line_2 ? ', ' + escapeHtml(address.address_line_2) : ''}
                    </div>
                    <div class="text-muted small">
                        ${escapeHtml(address.city)}, ${escapeHtml(address.state)} - ${escapeHtml(address.postal_code)}
                    </div>
                    <div class="text-muted small">
                        Phone: ${escapeHtml(address.phone || 'Not provided')}
                    </div>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="changeAddress()">
                    <i class="fas fa-edit me-1"></i>Change
                </button>
            </div>
        `;

        // Show COD confirmation section
        if (!isInitializing) {
            showCodConfirmationInline();
        }
    }

    function changeAddress() {
        document.getElementById('selectedAddressDisplay').classList.add('d-none');
        document.getElementById('addressListState').classList.remove('d-none');
        document.getElementById('codConfirmationSection').classList.add('d-none');
        document.getElementById('placeOrderBtnSection').classList.remove('d-none');
        selectedAddressObj = null;
        document.getElementById('selectedAddressId').value = '';
    }

    // ============================================
    // ADDRESS MODAL
    // ============================================

    function openAddressModal() {
        document.getElementById('addressForm').reset();
        addressModalInstance.show();
    }

    function saveAddress() {
        const formData = {
            name: document.getElementById('addrLabel').value,
            phone: document.getElementById('addrPhone').value,
            address_line_1: document.getElementById('addrLine1').value,
            address_line_2: document.getElementById('addrLine2').value,
            city: document.getElementById('addrCity').value,
            state: document.getElementById('addrState').value,
            postal_code: document.getElementById('addrPostal').value,
            is_default: document.getElementById('addrDefault').checked,
            _token: '{{ csrf_token() }}'
        };

        // Validate required fields
        if (!formData.name || !formData.address_line_1 || !formData.city || !formData.state || !formData.postal_code) {
            showNotification('Please fill all required fields', 'error');
            return;
        }

        fetch('{{ route("addresses.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    showNotification('Address saved successfully', 'success');
                    addressModalInstance.hide();
                    loadUserAddresses();
                } else {
                    showNotification(result.message || 'Error saving address', 'error');
                }
            })
            .catch(err => {
                showNotification('Network error. Please try again.', 'error');
            });
    }

    // ============================================
    // COD ORDER CONFIRMATION
    // ============================================

    function showCodConfirmationInline() {
        // Get total amount from feeData passed from controller
        let totalAmount = '0';
        @if(isset($feeData['grand_total']))
        totalAmount = '{{ $feeData["grand_total"] }}';
        @endif

        const codTotalAmountInline = document.getElementById('codTotalAmountInline');
        const codAddressInline = document.getElementById('codAddressInline');

        if (codTotalAmountInline) {
            codTotalAmountInline.textContent = totalAmount;
        }

        if (codAddressInline && selectedAddressObj) {
            codAddressInline.textContent = `${selectedAddressObj.address_line_1}, ${selectedAddressObj.city}`;
        }

        // Hide Place Order button and show confirmation section
        const placeOrderSection = document.getElementById('placeOrderBtnSection');
        const codConfirmationSection = document.getElementById('codConfirmationSection');

        if (placeOrderSection) {
            placeOrderSection.classList.add('d-none');
        }
        if (codConfirmationSection) {
            codConfirmationSection.classList.remove('d-none');
        }
    }

    function confirmCodOrderInline() {
        proceedWithOrderSubmission();
    }

    // ============================================
    // ORDER SUBMISSION
    // ============================================

    function submitOrder() {
        proceedWithOrderSubmission();
    }

    function proceedWithOrderSubmission() {
        // Validate address is selected
        if (!selectedAddressObj) {
            showNotification('Please select a delivery address', 'error');
            return;
        }

        const btn = document.querySelector('.btn-place-order');
        const originalHtml = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
        btn.disabled = true;

        const form = document.getElementById('checkoutForm');
        const formData = new FormData(form);

        fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    showNotification('Order placed successfully!', 'success');
                    
                    // Open WhatsApp with order details
                    if (result.whatsapp_message && result.whatsapp_number) {
                        const whatsappUrl = `https://wa.me/${result.whatsapp_number}?text=${result.whatsapp_message}`;
                        window.open(whatsappUrl, '_blank');
                    }
                    
                    setTimeout(() => {
                        window.location.href = result.redirect_url || '/order/success';
                    }, 1500);
                } else {
                    showNotification(result.message || 'Order failed. Please try again.', 'error');
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
            })
            .catch(err => {
                showNotification('Network error. Please check your connection.', 'error');
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
    }

    // ============================================
    // HELPER FUNCTIONS
    // ============================================

    function escapeHtml(str) {
        if (!str) return '';
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function showNotification(message, type) {
        // Remove existing notification
        const existing = document.querySelector('.checkout-notification');
        if (existing) existing.remove();

        // Create new notification
        const notif = document.createElement('div');
        notif.className = `checkout-notification ${type}`;
        notif.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${escapeHtml(message)}</span>
        `;

        document.body.appendChild(notif);

        // Animate in
        setTimeout(() => notif.classList.add('show'), 10);

        // Animate out
        setTimeout(() => {
            notif.classList.remove('show');
            setTimeout(() => notif.remove(), 300);
        }, 3000);
    }
</script>
@endsection