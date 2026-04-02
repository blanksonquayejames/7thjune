<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart()
    {
        if (auth()->check()) {
            $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        } else {
            $sessionId = session()->getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }
        return $cart;
    }

    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart->items()->with('product')->get();
        $total = $cart->total;

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->stock < 1) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Product is out of stock.'], 422);
            }
            return back()->with('error', 'Product is out of stock.');
        }

        $cart = $this->getCart();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            if ($cartItem->quantity + 1 > $product->stock) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => 'Not enough stock available.'], 422);
                }
                return back()->with('error', 'Not enough stock available.');
            }
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Product added to cart!']);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function buyNow(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->stock < 1) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Product is out of stock.'], 422);
            }
            return back()->with('error', 'Product is out of stock.');
        }

        $cart = $this->getCart();

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Product already in cart. Redirecting to checkout.', 'redirect' => route('checkout.index')]);
            }
            return redirect()->route('checkout.index')->with('success', 'Product already in cart. Redirecting to checkout.');
        }

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'quantity' => 1,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Product added to cart. Redirecting to checkout.', 'redirect' => route('checkout.index')]);
        }

        return redirect()->route('checkout.index')->with('success', 'Product added to cart. Redirecting to checkout.');
    }

    public function update(Request $request, $cartItemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::findOrFail($cartItemId);

        if ($request->quantity > $cartItem->product->stock) {
            return back()->with('error', 'Not enough stock available.');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated!');
    }

    public function remove($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
