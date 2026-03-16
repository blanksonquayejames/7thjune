<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();

        $featuredProducts = Product::where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        // Products grouped by category for the tabbed section
        $newArrivals = [];
        foreach ($categories->take(4) as $category) {
            $newArrivals[$category->slug] = [
                'name' => $category->name,
                'products' => Product::where('is_active', true)
                    ->where('category_id', $category->id)
                    ->latest()
                    ->take(6)
                    ->get(),
            ];
        }

        return view('home', compact('featuredProducts', 'categories', 'newArrivals'));
    }
}
