import { NextRequest, NextResponse } from 'next/server';
import { dbQuery } from '../../../../lib/db';

interface RouteParams {
  params: Promise<{ id: string }>;
}

interface DBOrderRow {
  id: number;
  user_id: number;
  total: string | number;
  status: string;
  shipping_address: string;
  phone: string;
  transaction_reference: string | null;
  created_at: string | Date;
  user_name: string;
  user_email: string;
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

// GET: Fetch a single order by ID (verifying userId if provided)
export async function GET(request: NextRequest, { params }: RouteParams) {
  try {
    const { id } = await params;
    const { searchParams } = request.nextUrl;
    const userId = searchParams.get('userId');

    let query = `
      SELECT o.*, u.name AS user_name, u.email AS user_email
      FROM orders o
      JOIN users u ON o.user_id = u.id
      WHERE o.id = $1
    `;
    const queryParams: any[] = [Number(id)];

    if (userId) {
      query += ' AND o.user_id = $2';
      queryParams.push(Number(userId));
    }

    const orders = await dbQuery<DBOrderRow>(query, queryParams);
    if (orders.length === 0) {
      return NextResponse.json({ error: 'Order not found' }, { status: 404 });
    }

    const order = orders[0];

    // Fetch order items
    const items = await dbQuery<DBOrderItemRow>(`
      SELECT oi.*, 
             p.name AS product_name, p.slug AS product_slug, p.image AS product_image
      FROM order_items oi
      JOIN products p ON oi.product_id = p.id
      WHERE oi.order_id = $1
    `, [order.id]);

    const formattedOrder = {
      id: order.id,
      user_id: order.user_id,
      total: Number(order.total),
      status: order.status,
      shipping_address: order.shipping_address,
      phone: order.phone,
      transaction_reference: order.transaction_reference,
      created_at: order.created_at,
      user: {
        id: order.user_id,
        name: order.user_name,
        email: order.user_email
      },
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

    return NextResponse.json(formattedOrder);
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// PUT: Update order status (Admin)
export async function PUT(request: NextRequest, { params }: RouteParams) {
  try {
    const { id } = await params;
    const { status } = await request.json();

    if (!status) {
      return NextResponse.json({ error: 'Status is required' }, { status: 400 });
    }

    await dbQuery('UPDATE orders SET status = $1 WHERE id = $2', [status, Number(id)]);

    // Fetch updated order
    const orders = await dbQuery<DBOrderRow>('SELECT * FROM orders WHERE id = $1', [Number(id)]);
    return NextResponse.json(orders[0]);
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
