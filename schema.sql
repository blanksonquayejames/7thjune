-- ==========================================
-- 7th June Computers Database Schema
-- Compatible with MySQL and PostgreSQL
-- ==========================================

CREATE DATABASE IF NOT EXISTS seventh_june_computers;
USE seventh_june_computers;

-- ── 1. CATEGORIES TABLE ──
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ── 2. USERS TABLE ──
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'customer', -- 'admin', 'customer'
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ── 3. PRODUCTS TABLE ──
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    price DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    description TEXT,
    image VARCHAR(255) DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    is_hot BOOLEAN DEFAULT FALSE,
    is_featured BOOLEAN DEFAULT FALSE,
    discount_percentage INT DEFAULT 0,
    discount_start DATETIME DEFAULT NULL,
    discount_end DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

-- ── 4. REVIEWS TABLE ──
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ── 5. ORDERS TABLE ──
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending', -- 'pending', 'processing', 'completed', 'cancelled'
    shipping_address TEXT NOT NULL,
    phone VARCHAR(50) NOT NULL,
    transaction_reference VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ── 6. ORDER ITEMS TABLE (Normalized relationship) ──
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL CHECK (quantity > 0),
    price DECIMAL(10, 2) NOT NULL, -- price at time of purchase
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);

-- ── INDEXES FOR PERFORMANCE OPTIMIZATION ──
CREATE INDEX idx_products_slug ON products(slug);
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_reviews_product ON reviews(product_id);


-- ==========================================
-- DATABASE VIEWS
-- ==========================================

-- ── View: Active products with their category name and discounted prices
CREATE OR REPLACE VIEW active_products_view AS
SELECT 
    p.id,
    p.name AS product_name,
    p.slug AS product_slug,
    p.price AS original_price,
    p.stock,
    p.discount_percentage,
    CASE 
        WHEN p.discount_percentage > 0 
             AND (p.discount_start IS NULL OR NOW() >= p.discount_start)
             AND (p.discount_end IS NULL OR NOW() <= p.discount_end)
        THEN ROUND(p.price * (1 - p.discount_percentage / 100.0), 2)
        ELSE p.price
    END AS current_price,
    c.name AS category_name,
    c.slug AS category_slug,
    p.is_hot,
    p.is_featured
FROM products p
JOIN categories c ON p.category_id = c.id
WHERE p.is_active = TRUE;

-- ── View: Product review details with reviewer names
CREATE OR REPLACE VIEW product_reviews_detail_view AS
SELECT 
    r.id AS review_id,
    p.id AS product_id,
    p.name AS product_name,
    u.name AS reviewer_name,
    u.email AS reviewer_email,
    r.rating,
    r.comment,
    r.created_at
FROM reviews r
JOIN products p ON r.product_id = p.id
JOIN users u ON r.user_id = u.id;


-- ==========================================
-- SEED DATA (Based on existing db.json)
-- ==========================================

-- Populate Categories
INSERT INTO categories (id, name, slug) VALUES
(1, 'Computers', 'computers'),
(2, 'Storage & Components', 'storage-components'),
(3, 'Networking', 'networking'),
(4, 'Tablets', 'tablets'),
(5, 'Peripherals', 'peripherals');

-- Populate Users
INSERT INTO users (id, name, email, password, role) VALUES
(1, 'Admin User', 'admin@7thjunecomputers.com', 'password', 'admin'),
(2, 'John Customer', 'user@7thjunecomputers.com', 'password', 'customer');

-- Populate Products
INSERT INTO products (id, category_id, name, slug, price, stock, description, is_active, is_hot, is_featured, discount_percentage, discount_start, discount_end) VALUES
(1, 1, 'ProBook 15 Laptop', 'probook-15-laptop', 799.99, 50, 'Reliable 15-inch laptop for daily productivity with 16GB RAM and 512GB SSD.', TRUE, TRUE, TRUE, 10, '2025-01-01 00:00:00', '2027-12-31 23:59:59'),
(2, 1, 'Gaming Elite Laptop', 'gaming-elite-laptop', 1499.99, 20, 'High-end gaming laptop featuring RTX 4060 graphics, 144Hz display, and RGB keyboard.', TRUE, TRUE, FALSE, 0, NULL, NULL),
(3, 1, 'Office Desktop PC', 'office-desktop-pc', 599.99, 30, 'Compact desktop computer perfect for office tasks, featuring a fast processor and 1TB storage.', TRUE, FALSE, TRUE, 0, NULL, NULL),
(4, 1, 'Creator Workstation', 'creator-workstation', 1999.99, 15, 'Powerful desktop workstation optimized for video editing, 3D rendering, and heavy workloads.', TRUE, FALSE, FALSE, 15, '2025-01-01 00:00:00', '2027-12-31 23:59:59'),
(5, 2, '1TB External HDD', '1tb-external-hdd', 59.99, 100, 'Portable 1TB external hard drive with USB 3.0 interface for fast data transfer.', TRUE, FALSE, TRUE, 0, NULL, NULL),
(6, 2, '2TB Internal SATA HDD', '2tb-internal-sata-hdd', 69.99, 80, 'Reliable 3.5-inch 2TB desktop internal hard drive for expanding your storage capacity.', TRUE, TRUE, FALSE, 0, NULL, NULL),
(7, 2, '4TB Network Attached Storage', '4tb-network-attached-storage', 199.99, 25, 'Personal cloud storage solution with 4TB capacity for automated backups.', TRUE, FALSE, TRUE, 20, '2025-01-01 00:00:00', '2027-12-31 23:59:59'),
(8, 3, '5-Port Gigabit Switch', '5-port-gigabit-switch', 19.99, 120, 'Unmanaged 5-port gigabit ethernet switch for expanding your home or office network.', TRUE, FALSE, FALSE, 0, NULL, NULL),
(9, 3, '24-Port Managed Switch', '24-port-managed-switch', 149.99, 15, 'Rack-mountable 24-port managed switch for enterprise networking needs.', TRUE, FALSE, TRUE, 0, NULL, NULL),
(10, 3, 'Cat6 Ethernet Cable 10ft', 'cat6-ethernet-cable-10ft', 9.99, 200, 'High-quality 10ft Cat6 patch cable for reliable wired network connections.', TRUE, TRUE, FALSE, 0, NULL, NULL),
(11, 3, 'Cat5e Ethernet Cable 50ft', 'cat5e-ethernet-cable-50ft', 14.99, 150, 'Long 50ft Cat5e cable, perfect for connecting devices across large rooms.', TRUE, FALSE, FALSE, 0, NULL, NULL),
(12, 4, 'Kids Tablet 8-inch', 'kids-tablet-8-inch', 89.99, 60, 'Durable 8-inch tablet designed for kids, featuring parental controls and a protective bumper case.', TRUE, FALSE, FALSE, 0, NULL, NULL),
(13, 4, 'Kids Tablet Pro 10-inch', 'kids-tablet-pro-10-inch', 129.99, 40, 'Larger 10-inch kids tablet with an HD display, educational apps, and robust battery life.', TRUE, FALSE, FALSE, 5, '2025-01-01 00:00:00', '2027-12-31 23:59:59'),
(14, 5, 'Ergonomic Wireless Mouse', 'ergonomic-wireless-mouse', 29.99, 85, 'Comfortable wireless mouse with ergonomic design, adjustable DPI, and long battery life.', TRUE, TRUE, FALSE, 0, NULL, NULL),
(15, 5, 'Mechanical Gaming Keyboard', 'mechanical-gaming-keyboard', 79.99, 45, 'Tactile mechanical keyboard with customizable RGB backlighting and anti-ghosting keys.', TRUE, FALSE, FALSE, 0, NULL, NULL),
(16, 5, 'Wireless Keyboard and Mouse Combo', 'wireless-keyboard-and-mouse-combo', 49.99, 110, 'Sleek wireless keyboard and optical mouse bundle, connecting via a single USB receiver.', TRUE, FALSE, FALSE, 0, NULL, NULL),
(17, 5, '1080p Webcam', '1080p-webcam', 39.99, 70, 'High-definition webcam with built-in microphone for clear video calls and streaming.', TRUE, FALSE, TRUE, 0, NULL, NULL);

-- Populate Reviews
INSERT INTO reviews (id, product_id, user_id, rating, comment) VALUES
(1, 1, 2, 5, 'Excellent laptop, great performance for the price!'),
(2, 1, 2, 4, 'Good build quality, battery could be better.'),
(3, 2, 2, 5, 'Beast gaming machine! Runs everything on ultra.'),
(4, 5, 2, 4, 'Reliable and portable. Good for backups.'),
(5, 14, 2, 5, 'Very comfortable for long work sessions.');

-- Populate Orders
INSERT INTO orders (id, user_id, total, status, shipping_address, phone, transaction_reference, created_at) VALUES
(1, 2, 863.99, 'processing', '123 Main St, Accra, Greater Accra, Ghana', '+233 20 123 4567', 'ref_001', '2026-04-20 10:30:00');

-- Populate Order Items
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 719.99),
(1, 5, 2, 59.99);
