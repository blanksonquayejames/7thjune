<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

echo "Starting image download for products...\n";

// Ensure the directory exists
Storage::disk('public')->makeDirectory('products');

$products = Product::all();

foreach ($products as $product) {
    echo "Downloading image for product ID: {$product->id} - {$product->name}\n";
    
    try {
        // Use dummyimage.com to generate an image with the product name
        $encodedName = urlencode($product->name);
        $imageUrl = "https://dummyimage.com/600x600/e0e0e0/000000.png&text={$encodedName}";
        
        $context = stream_context_create([
            'http' => [
                'timeout' => 10,
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
            ]
        ]);
        
        $imageContents = @file_get_contents($imageUrl, false, $context);
        
        if ($imageContents) {
            $filename = 'products/' . Str::slug($product->name) . '-' . time() . '.jpg';
            Storage::disk('public')->put($filename, $imageContents);
            
            $product->image = $filename;
            $product->save();
            
            echo "Successfully saved as {$filename}\n";
        } else {
            echo "Failed to download image for product ID: {$product->id}\n";
        }
    } catch (\Exception $e) {
         echo "Error for product {$product->id}: " . $e->getMessage() . "\n";
    }
}

echo "Finished downloading images.\n";
