import { NextResponse } from 'next/server';
import { dbQuery, dbTransaction } from '../../../lib/db';

// GET: Fetch user orders OR all orders (Admin)
export async function GET(request) {
  try {
    const { searchParams } = request.nextUrl;
    const userId = searchParams.get('userId');
    const all = searchParams.get('all') === 'true';

    let orders;
    if (userId && !all) {
      orders = await dbQuery(
        'SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC',
        [Number(userId)]
      );
    } else {
      // Admin view: include user details
      orders = await dbQuery(`
        SELECT o.*, u.name AS user_name, u.email AS user_email
        FROM orders o
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC
      `);
    }

    // Populate items for each order
    const formattedOrders = await Promise.all(
      orders.map(async (order) => {
        const items = await dbQuery(`
          SELECT oi.*, 
                 p.name AS product_name, p.slug AS product_slug, p.image AS product_image
          FROM order_items oi
          JOIN products p ON oi.product_id = p.id
          WHERE oi.order_id = ?
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
  } catch (error) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// POST: Place a new order with transaction safety
export async function POST(request) {
  try {
    const data = await request.json();
    const { user_id, total, shipping_address, phone, transaction_reference, items } = data;

    if (!user_id || !total || !shipping_address || !phone || !items || items.length === 0) {
      return NextResponse.json({ error: 'Missing required order details' }, { status: 400 });
    }

    // Run transaction
    const newOrder = await dbTransaction(async (conn) => {
      // 1. Insert into orders table
      const [orderResult] = await conn.execute(`
        INSERT INTO orders (user_id, total, status, shipping_address, phone, transaction_reference, created_at)
        VALUES (?, ?, 'pending', ?, ?, ?, NOW())
      `, [
        Number(user_id),
        Number(total),
        shipping_address,
        phone,
        transaction_reference || null
      ]);

      const orderId = orderResult.insertId;

      // 2. Loop through items to insert order items and decrement stock
      for (const item of items) {
        // Insert item record
        await conn.execute(`
          INSERT INTO order_items (order_id, product_id, quantity, price)
          VALUES (?, ?, ?, ?)
        `, [
          orderId,
          Number(item.product_id),
          Number(item.quantity),
          Number(item.price)
        ]);

        // Decrement product stock
        await conn.execute(`
          UPDATE products 
          SET stock = GREATEST(0, stock - ?) 
          WHERE id = ?
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
  } catch (error) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
