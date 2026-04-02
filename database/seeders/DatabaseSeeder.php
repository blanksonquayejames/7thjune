<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@7thjunecomputers.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create test customer
        User::create([
            'name' => 'John Customer',
            'email' => 'user@7thjunecomputers.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Create categories
        $categories = [
            ['name' => 'Computers', 'slug' => 'computers', 'image' => null],
            ['name' => 'Storage & Components', 'slug' => 'storage-components', 'image' => null],
            ['name' => 'Networking', 'slug' => 'networking', 'image' => null],
            ['name' => 'Tablets', 'slug' => 'tablets', 'image' => null],
            ['name' => 'Peripherals', 'slug' => 'peripherals', 'image' => null],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Create products
        $products = [
            // Computers
            ['category_id' => 1, 'name' => 'ProBook 15 Laptop', 'price' => 799.99, 'stock' => 50, 'description' => 'Reliable 15-inch laptop for daily productivity with 16GB RAM and 512GB SSD.'],
            ['category_id' => 1, 'name' => 'Gaming Elite Laptop', 'price' => 1499.99, 'stock' => 20, 'description' => 'High-end gaming laptop featuring RTX 4060 graphics, 144Hz display, and RGB keyboard.'],
            ['category_id' => 1, 'name' => 'Office Desktop PC', 'price' => 599.99, 'stock' => 30, 'description' => 'Compact desktop computer perfect for office tasks, featuring a fast processor and 1TB storage.'],
            ['category_id' => 1, 'name' => 'Creator Workstation', 'price' => 1999.99, 'stock' => 15, 'description' => 'Powerful desktop workstation optimized for video editing, 3D rendering, and heavy workloads.'],

            // Storage & Components
            ['category_id' => 2, 'name' => '1TB External HDD', 'price' => 59.99, 'stock' => 100, 'description' => 'Portable 1TB external hard drive with USB 3.0 interface for fast data transfer.'],
            ['category_id' => 2, 'name' => '2TB Internal SATA HDD', 'price' => 69.99, 'stock' => 80, 'description' => 'Reliable 3.5-inch 2TB desktop internal hard drive for expanding your storage capacity.'],
            ['category_id' => 2, 'name' => '4TB Network Attached Storage', 'price' => 199.99, 'stock' => 25, 'description' => 'Personal cloud storage solution with 4TB capacity for automated backups.'],

            // Networking
            ['category_id' => 3, 'name' => '5-Port Gigabit Switch', 'price' => 19.99, 'stock' => 120, 'description' => 'Unmanaged 5-port gigabit ethernet switch for expanding your home or office network.'],
            ['category_id' => 3, 'name' => '24-Port Managed Switch', 'price' => 149.99, 'stock' => 15, 'description' => 'Rack-mountable 24-port managed switch for enterprise networking needs.'],
            ['category_id' => 3, 'name' => 'Cat6 Ethernet Cable 10ft', 'price' => 9.99, 'stock' => 200, 'description' => 'High-quality 10ft Cat6 patch cable for reliable wired network connections.'],
            ['category_id' => 3, 'name' => 'Cat5e Ethernet Cable 50ft', 'price' => 14.99, 'stock' => 150, 'description' => 'Long 50ft Cat5e cable, perfect for connecting devices across large rooms.'],

            // Tablets
            ['category_id' => 4, 'name' => 'Kids Tablet 8-inch', 'price' => 89.99, 'stock' => 60, 'description' => 'Durable 8-inch tablet designed for kids, featuring parental controls and a protective bumper case.'],
            ['category_id' => 4, 'name' => 'Kids Tablet Pro 10-inch', 'price' => 129.99, 'stock' => 40, 'description' => 'Larger 10-inch kids tablet with an HD display, educational apps, and robust battery life.'],

            // Peripherals
            ['category_id' => 5, 'name' => 'Ergonomic Wireless Mouse', 'price' => 29.99, 'stock' => 85, 'description' => 'Comfortable wireless mouse with ergonomic design, adjustable DPI, and long battery life.'],
            ['category_id' => 5, 'name' => 'Mechanical Gaming Keyboard', 'price' => 79.99, 'stock' => 45, 'description' => 'Tactile mechanical keyboard with customizable RGB backlighting and anti-ghosting keys.'],
            ['category_id' => 5, 'name' => 'Wireless Keyboard and Mouse Combo', 'price' => 49.99, 'stock' => 110, 'description' => 'Sleek wireless keyboard and optical mouse bundle, connecting via a single USB receiver.'],
            ['category_id' => 5, 'name' => '1080p Webcam', 'price' => 39.99, 'stock' => 70, 'description' => 'High-definition webcam with built-in microphone for clear video calls and streaming.'],
        ];

        foreach ($products as $index => $product) {
            Product::create(array_merge($product, [
                'slug' => Str::slug($product['name']),
                'is_active' => true,
                'is_hot' => in_array($index, [0, 1, 5, 9, 13]),
                'is_featured' => in_array($index, [0, 2, 4, 6, 8, 12, 16, 18]),
            ]));
        }
    }
}
