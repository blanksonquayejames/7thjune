import { NextResponse } from 'next/server';
import { dbQuery } from '../../../lib/db';

// POST: Submit a product review
export async function POST(request) {
  try {
    const { productId, userId, rating, comment } = await request.json();

    if (!productId || !userId || !rating) {
      return NextResponse.json({ error: 'Product ID, User ID, and Rating are required' }, { status: 400 });
    }

    const result = await dbQuery(
      'INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)',
      [Number(productId), Number(userId), Number(rating), comment || null]
    );

    const newId = result.insertId;

    // Fetch the new review joined with user details
    const reviews = await dbQuery(`
      SELECT r.id, r.product_id, r.user_id, r.rating, r.comment, r.created_at,
             u.name AS user_name, u.email AS user_email
      FROM reviews r
      JOIN users u ON r.user_id = u.id
      WHERE r.id = ?
    `, [newId]);

    const review = reviews[0];

    return NextResponse.json({
      id: review.id,
      product_id: review.product_id,
      user_id: review.user_id,
      rating: review.rating,
      comment: review.comment,
      created_at: review.created_at,
      user: {
        id: review.user_id,
        name: review.user_name,
        email: review.user_email
      }
    });
  } catch (error) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
