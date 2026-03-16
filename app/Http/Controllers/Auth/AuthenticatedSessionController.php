<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Capture guest session ID BEFORE authentication (session regenerates after login)
        $guestSessionId = session()->getId();

        $request->authenticate();

        $request->session()->regenerate();

        // Merge guest cart into the logged-in user's cart
        $this->mergeGuestCart($guestSessionId, Auth::id());

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Merge a guest (session-based) cart into the authenticated user's cart.
     */
    private function mergeGuestCart(string $guestSessionId, int $userId): void
    {
        $guestCart = Cart::where('session_id', $guestSessionId)->first();

        if (!$guestCart) {
            return; // No guest cart to merge
        }

        // Get or create the user's cart
        $userCart = Cart::firstOrCreate(['user_id' => $userId]);

        // Move each guest item into the user's cart
        foreach ($guestCart->items as $guestItem) {
            $existingItem = CartItem::where('cart_id', $userCart->id)
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($existingItem) {
                // If product already in user's cart, add the quantities
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $guestItem->quantity,
                ]);
            } else {
                // Move the item to the user's cart
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }

        // Delete the now-empty guest cart
        $guestCart->items()->delete(); // clean up any remaining items
        $guestCart->delete();
    }
}
