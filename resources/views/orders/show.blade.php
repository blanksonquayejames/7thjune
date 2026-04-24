@extends('layouts.store')
@section('title', 'Order Confirmed - #' . $order->id)

@push('styles')
<style>
    .order-confirmed-container { max-width: 600px; margin: 0 auto; text-align: center; padding: 60px 20px; }
    .success-icon-wrapper { margin-bottom: 30px; display: flex; justify-content: center; }
    .success-icon { display: flex; align-items: center; justify-content: center; width: 80px; height: 80px; background-color: #22c55e; border-radius: 50%; color: white; font-size: 40px; box-shadow: 0 4px 15px rgba(34, 197, 94, 0.3); }
    
    .confirmed-title { text-transform: uppercase; font-weight: 900; font-size: 2rem; margin-bottom: 40px; color: #000; }
    .thank-you-msg { font-weight: 800; font-size: 1.5rem; margin-bottom: 20px; color: #000; }
    
    .order-details { font-size: 1rem; color: #000; margin-bottom: 40px; line-height: 1.6; }
    .order-details strong { font-weight: 700; text-transform: uppercase; }
    
    .btn-blue-block { display: inline-block; background-color: #0c56d0; color: white; text-transform: uppercase; font-weight: 800; border-radius: 4px; padding: 14px 30px; border: none; font-size: 0.9rem; text-decoration: none; transition: background-color 0.2s; }
    .btn-blue-block:hover { background-color: #0d47a1; color: white; }
</style>
@endpush

@section('content')
<!-- Optional matching top nav style gap if needed -->
<div style="height: 10px; background: linear-gradient(90deg, #0c56d0 0%, #000 100%); width: 100%;"></div>

<div class="container pb-5 pt-3">
    <div class="order-confirmed-container bg-white" style="border-radius: 8px;">
        
        <h1 class="confirmed-title">ORDER CONFIRMED!</h1>
        
        <div class="success-icon-wrapper">
            <div class="success-icon">
                <i class="bi bi-check-lg"></i>
            </div>
        </div>
        
        <h2 class="thank-you-msg">Thank you for your purchase, {{ auth()->user()->name ?? 'Guest' }}!</h2>
        
        <div class="order-details">
            <strong>ORDER NUMBER:</strong> UT-{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}<br><br>
            @php
                $deliveryStart = $order->created_at->addDays(3)->format('d M');
                $deliveryEnd = $order->created_at->addDays(5)->format('d M Y');
                $arrivingBy = $order->created_at->addDays(5)->format('l, M jS');
            @endphp
            Delivery Estimate {{ $deliveryStart }}-{{ $deliveryEnd }}<br>
            Arriving by [{{ $arrivingBy }}]
        </div>
        
        <a href="{{ route('products.index') }}" class="btn-blue-block">
            BACK TO SHOPPING
        </a>
        
    </div>
</div>
@endsection
