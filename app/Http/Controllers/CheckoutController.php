<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::where('user_id', auth()->id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = $cart->items()->with('product')->get();
        $total = $cart->total;

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'shipping_method' => 'required|string',
            'payment_method' => 'required|string',
            'paystack_reference' => 'nullable|string',
        ]);

        $cart = Cart::where('user_id', auth()->id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            $subtotal = $cart->total;
            $tax = $subtotal * 0.075;
            $total = $subtotal + $tax;

            // Combine address parts into one string to be saved in the text column
            $addressParts = array_filter([
                $request->street,
                $request->city,
                $request->region,
                $request->country
            ]);
            
            $shipping_address = implode(', ', $addressParts);
            $shipping_address .= ' | Shipping: ' . $request->shipping_method;
            $shipping_address .= ' | Payment: ' . $request->payment_method;

            // Use the phone number provided in the form
            $phone = $request->phone;

            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'pending',
                'shipping_address' => $shipping_address,
                'phone' => $phone,
                'transaction_reference' => $request->paystack_reference,
            ]);

            foreach ($cart->items()->with('product')->get() as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->discounted_price,
                ]);

                // Decrement stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart
            $cart->items()->delete();

            DB::commit();

            return redirect()->route('orders.show', $order->id)
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again. ' . $e->getMessage());
        }
    }
}
