import { NextRequest, NextResponse } from 'next/server';
import { dbQuery, dbTransaction } from '../../../lib/db';
import type { Order, OrderItem } from '../../../lib/types';

interface DBOrderRow {
  id: number;
  user_id: number;
  total: string | number;
  status: string;
  shipping_address: string;
  phone: string;
  transaction_reference: string | null;
  created_at: string | Date;
  user_name?: string;
  user_email?: string;
}

interface DBOrderItemRow {
  id: number;
  order_id: number;
  product_id: number;
  quantity: number;
  price: string | number;
  product_name: string;
  product_slug: string;
  product_image: string | null;
}

interface OrderRequestItem {
  product_id: string | number;
  quantity: string | number;
  price: string | number;
}

// GET: Fetch user orders OR all orders (Admin)
export async function GET(request: NextRequest) {
  try {
    const { searchParams } = request.nextUrl;
    const userId = searchParams.get('userId');
    const all = searchParams.get('all') === 'true';

    let orders: DBOrderRow[];
    if (userId && !all) {
      orders = await dbQuery<DBOrderRow>(
        'SELECT * FROM orders WHERE user_id = $1 ORDER BY created_at DESC',
        [Number(userId)]
      );
    } else {
      // Admin view: include user details
      orders = await dbQuery<DBOrderRow>(`
        SELECT o.*, u.name AS user_name, u.email AS user_email
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC
      `);
    }

    // Populate items for each order
    const formattedOrders = await Promise.all(
      orders.map(async (order) => {
        const items = await dbQuery<DBOrderItemRow>(`
          SELECT oi.*, 
                 p.name AS product_name, p.slug AS product_slug, p.image AS product_image
          FROM order_items oi
          JOIN products p ON oi.product_id = p.id
          WHERE oi.order_id = $1
        `, [order.id]);

        return {
          id: order.id,
          user_id: order.user_id,
          total: Number(order.total),
          status: order.status,
          shipping_address: order.shipping_address,
          phone: order.phone,
          transaction_reference: order.transaction_reference,
          created_at: order.created_at,
          user: order.user_name ? {
            id: order.user_id,
            name: order.user_name,
            email: order.user_email
          } : undefined,
          items: items.map(item => ({
            id: item.id,
            order_id: item.order_id,
            product_id: item.product_id,
            quantity: item.quantity,
            price: Number(item.price),
            product: {
              id: item.product_id,
              name: item.product_name,
              slug: item.product_slug,
              image: item.product_image
            }
          }))
        };
      })
    );

    return NextResponse.json(formattedOrders);
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// POST: Place a new order with transaction safety
export async function POST(request: NextRequest) {
  try {
    const data = await request.json();
    const { user_id, total, shipping_address, phone, transaction_reference, items } = data as {
      user_id: string | number;
      total: string | number;
      shipping_address: string;
      phone: string;
      transaction_reference?: string | null;
      items: OrderRequestItem[];
    };

    if (!user_id || !total || !shipping_address || !phone || !items || items.length === 0) {
      return NextResponse.json({ error: 'Missing required order details' }, { status: 400 });
    }

    // Run transaction
    const newOrder = await dbTransaction(async (conn) => {
      // 1. Insert into orders table
      const orderResult = await conn.query<{ id: number }>(`
        INSERT INTO orders (user_id, total, status, shipping_address, phone, transaction_reference, created_at)
        VALUES ($1, $2, 'pending', $3, $4, $5, CURRENT_TIMESTAMP)
        RETURNING id
      `, [
        Number(user_id),
        Number(total),
        shipping_address,
        phone,
        transaction_reference || null
      ]);

      const orderId = orderResult.rows[0].id;

      // 2. Loop through items to insert order items and decrement stock
      for (const item of items) {
        // Insert item record
        await conn.query(`
          INSERT INTO order_items (order_id, product_id, quantity, price)
          VALUES ($1, $2, $3, $4)
        `, [
          orderId,
          Number(item.product_id),
          Number(item.quantity),
          Number(item.price)
        ]);

        // Decrement product stock
        await conn.query(`
          UPDATE products 
          SET stock = GREATEST(0, stock - $1) 
          WHERE id = $2
        `, [
          Number(item.quantity),
          Number(item.product_id)
        ]);
      }

      return {
        id: orderId,
        user_id,
        total,
        status: 'pending',
        shipping_address,
        phone,
        transaction_reference
      };
    });

    return NextResponse.json(newOrder);
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
