<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('user_id', auth()->id())
            ->where('id', $id)
            ->with('items.product')
            ->firstOrFail();

        return view('orders.show', compact('order'));
    }
}
