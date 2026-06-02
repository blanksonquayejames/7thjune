export interface Category {
  id: number;
  name: string;
  slug: string;
  image: string | null;
  created_at?: Date | string;
  updated_at?: Date | string;
  products_count?: number | string;
}

export interface User {
  id: number;
  name: string;
  email: string;
  password?: string;
  role: 'admin' | 'customer';
  created_at?: Date | string;
  updated_at?: Date | string;
}

export interface Product {
  id: number;
  category_id: number;
  name: string;
  slug: string;
  price: number;
  stock: number;
  description: string | null;
  image: string | null;
  is_active: boolean;
  is_hot: boolean;
  is_featured: boolean;
  discount_percentage: number;
  discount_start: string | null;
  discount_end: string | null;
  created_at?: Date | string;
  updated_at?: Date | string;
  category_name?: string;
  category_slug?: string;
  category_image?: string | null;
}

export interface Review {
  id: number;
  product_id: number;
  user_id: number;
  rating: number;
  comment: string | null;
  created_at: Date | string;
  user_name?: string;
  user_email?: string;
}

export interface Order {
  id: number;
  user_id: number;
  total: number;
  status: string;
  shipping_address: string;
  phone: string;
  transaction_reference: string | null;
  created_at: Date | string;
  updated_at?: Date | string;
  user_name?: string;
  user_email?: string;
}

export interface OrderItem {
  id: number;
  order_id: number;
  product_id: number;
  quantity: number;
  price: number;
  product_name?: string;
  product_slug?: string;
  product_image?: string | null;
}
