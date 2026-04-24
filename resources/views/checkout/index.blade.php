@extends('layouts.store')
@section('title', 'Checkout')

@push('styles')
    <style>
        /* Checkout specific styles for mockup replica */
        .checkout-header {
            text-transform: uppercase;
            font-weight: 900;
            font-size: 1.8rem;
            margin-bottom: 2rem;
            color: #000;
        }

        .form-label {
            font-weight: 700;
            color: #000;
            font-size: 0.9rem;
        }

        .form-control-custom {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 12px;
            font-size: 0.95rem;
        }

        .form-control-custom:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        .bg-light-blue {
            background-color: #eef2ff !important;
            border-color: #c7d2fe !important;
        }

        .custom-select {
            appearance: none;
            background: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E") no-repeat right 12px center;
            background-color: #fff;
            background-size: 16px;
        }

        .radio-label {
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            margin-bottom: 12px;
            cursor: pointer;
            color: #000;
        }

        .radio-label input[type="radio"] {
            margin-right: 12px;
            width: 18px;
            height: 18px;
            accent-color: #2563eb;
        }

        .payment-card {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 12px;
            transition: all 0.2s;
            cursor: pointer;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .payment-card.active {
            border: 2px solid #2563eb;
            background-color: #eff6ff;
        }

        .payment-logo {
            height: 28px;
            width: auto;
            object-fit: contain;
            border-radius: 4px;
        }

        .btn-block-blue {
            background-color: #0c56d0;
            color: white;
            text-transform: uppercase;
            font-weight: 800;
            border-radius: 4px;
            padding: 16px;
            width: 100%;
            border: none;
            font-size: 1rem;
        }

        .btn-block-blue:hover {
            background-color: #0d47a1;
            color: white;
        }

        .order-summary-box {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            color: #000;
        }

        .order-summary-box .title {
            font-weight: 800;
            font-size: 1rem;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .summary-row.grand-total {
            font-weight: 800;
            font-size: 1.1rem;
            border-top: 1px solid #ddd;
            padding-top: 12px;
            margin-top: 12px;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #64748b;
            font-weight: bold;
            position: absolute;
            top: -20px;
            right: -20px;
        }

        .momo-input {
            margin-top: 10px;
            margin-left: 40px;
            width: calc(100% - 40px);
        }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
    <div class="container py-5" x-data="checkoutData()">
        <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
            @csrf

            <div class="row g-5">
                <!-- Left Column: Forms -->
                <div class="col-lg-7 position-relative">

                    <!-- STEP 1: SHIPPING -->
                    <div x-show="step === 1" x-transition.opacity.duration.300ms>
                        <h2 class="checkout-header">CHECKOUT: SHIPPING</h2>

                        <div class="mb-4">
                            <h4 class="fw-bold mb-3">Shipping Address</h4>

                            <div class="mb-3">
                                <label class="form-label">Street Address (e.g.)</label>
                                <input type="text" name="street" x-model="form.street"
                                    class="form-control form-control-custom" placeholder="123 Harbour Road" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" x-model="form.city"
                                    class="form-control form-control-custom bg-light-blue" placeholder="Takoradi" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Region</label>
                                <select name="region" x-model="form.region"
                                    class="form-control form-control-custom custom-select" required>
                                    <option value="">Select Region</option>
                                    <option value="Western Region">Western Region</option>
                                    <option value="Greater Accra">Greater Accra</option>
                                    <option value="Ashanti Region">Ashanti Region</option>
                                    <option value="Central Region">Central Region</option>
                                    <option value="Eastern Region">Eastern Region</option>
                                    <option value="Northern Region">Northern Region</option>
                                    <option value="Volta Region">Volta Region</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone" x-model="form.phone"
                                    class="form-control form-control-custom" placeholder="e.g. 024XXXXXXX" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Country</label>
                                <select name="country" x-model="form.country"
                                    class="form-control form-control-custom custom-select" required>
                                    <option value="Ghana">Ghana</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h4 class="fw-bold mb-3">Shipping Method</h4>
                            <label class="radio-label">
                                <input type="radio" name="shipping_method" value="Ghana Standard" checked>
                                Ghana Standard (3-5 days)
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="shipping_method" value="Express">
                                Express (Accra/Takoradi next day)
                            </label>
                        </div>

                        <button type="button" @click="nextStep()" class="btn btn-block-blue" :disabled="!isStep1Valid()">
                            CONTINUE TO PAYMENT
                        </button>
                    </div>

                    <!-- STEP 2: PAYMENT -->
                    <div x-show="step === 2" x-transition.opacity.duration.300ms style="display: none;">
                        <h2 class="checkout-header">CHECKOUT: PAYMENT</h2>

                        <div class="mb-4">
                            <h4 class="fw-bold mb-3">Payment Method</h4>

                            <!-- Mobile Money (Unified) -->
                            <div class="payment-card" :class="{'active': paymentMethod === 'mobile_money'}"
                                @click="paymentMethod = 'mobile_money'">
                                <input type="radio" name="payment_method" value="mobile_money" x-model="paymentMethod"
                                    class="d-none">
                                <i class="bi bi-phone-vibrate text-success fs-3 ms-1 me-1"></i>
                                <span class="fw-bold">Mobile Money (MTN, AirtelTigo, Vodafone)</span>
                            </div>

                            <!-- Credit/Debit Card (Disabled) -->
                            <div class="payment-card opacity-50" style="cursor: not-allowed; background-color: #f8f9fa;">
                                <input type="radio" name="payment_method" value="card" disabled class="d-none">
                                <i class="bi bi-credit-card-2-front text-muted fs-3 ms-1 me-1"></i>
                                <span class="fw-bold text-muted">Credit/Debit Card (Coming Soon)</span>
                            </div>

                            <!-- PayPal (Disabled) -->
                            <div class="payment-card opacity-50" style="cursor: not-allowed; background-color: #f8f9fa;">
                                <input type="radio" name="payment_method" value="paypal" disabled class="d-none">
                                <i class="bi bi-paypal text-muted fs-3 ms-1 me-1"></i>
                                <span class="fw-bold text-muted">PayPal (Coming Soon)</span>
                            </div>
                        </div>

                        <div class="mb-4 d-flex align-items-center">
                            <label class="fw-bold me-3 text-dark">Billing Address</label>
                            <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
                                <input class="form-check-input mt-0" type="checkbox" role="switch" id="matchAddress" checked
                                    style="width:2.5em;height:1.25em;cursor:pointer;">
                                <label class="form-check-label text-dark" for="matchAddress" style="cursor:pointer;">Match
                                    as Address</label>
                            </div>
                        </div>

                        <button type="button" @click="step = 1"
                            class="btn btn-link text-decoration-none text-muted p-0 mb-3">
                            <i class="bi bi-arrow-left me-1"></i> Back to Shipping
                        </button>

                        <!-- Mobile summary button (visible on small screens only) -->
                        <button type="button" @click="payWithPaystack()" class="btn btn-block-blue d-lg-none">
                            COMPLETE ORDER (₵{{ number_format($total + ($total * 0.075), 2) }})
                        </button>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="col-lg-5" x-show="step === 2" x-transition style="display: none;">
                    <div class="order-summary-box">
                        <h3 class="title">ORDER SUMMARY</h3>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₵{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        <div class="summary-row">
                            <span>Tax</span>
                            <span>₵{{ number_format($total * 0.075, 2) }}</span>
                        </div>
                        <div class="summary-row grand-total">
                            <span>Grand Total</span>
                            <span>₵{{ number_format($total + ($total * 0.075), 2) }}</span>
                        </div>
                    </div>

                    <input type="hidden" name="paystack_reference" x-model="paystackReference">
                    <button type="button" @click="payWithPaystack()" class="btn btn-block-blue mt-4 d-none d-lg-block">
                        COMPLETE ORDER (₵{{ number_format($total + ($total * 0.075), 2) }})
                    </button>
                </div>

            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://js.paystack.co/v1/inline.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('checkoutData', () => ({
                    step: 1,
                    paymentMethod: 'mobile_money',
                    paystackReference: '',
                    publicKey: '{{ config('services.paystack.public_key') }}',
                    userEmail: '{{ auth()->user()->email ?? "guest@example.com" }}',
                    // convert total to pesewas/kobo
                    amountInPesewas: {{ round(($total + ($total * 0.075)) * 100) }},
                    form: {
                        street: '{{ old('street') }}',
                        city: '{{ old('city') }}',
                        region: '{{ old('region') }}',
                        phone: '{{ old('phone') }}',
                        country: 'Ghana'
                    },

                    isStep1Valid() {
                        return this.form.street.trim() !== '' &&
                            this.form.city.trim() !== '' &&
                            this.form.region.trim() !== '';
                    },

                    nextStep() {
                        if (this.isStep1Valid()) {
                            this.step = 2;
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    },

                    payWithPaystack() {
                        if (!this.publicKey) {
                            alert('Paystack public key is not set!');
                            return;
                        }

                        let handler = PaystackPop.setup({
                            key: this.publicKey,
                            email: this.userEmail,
                            amount: this.amountInPesewas,
                            currency: 'GHS',
                            ref: 'UT_' + Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference
                            callback: (response) => {
                                // success callback
                                this.paystackReference = response.reference;
                                // small delay to ensure binding settles
                                setTimeout(() => {
                                    document.getElementById('checkout-form').submit();
                                }, 200);
                            },
                            onClose: () => {
                                alert('Payment window closed.');
                            }
                        });
                        handler.openIframe();
                    }
                }));
            });
        </script>
    @endpush

@endsection