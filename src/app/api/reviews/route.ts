import { NextRequest, NextResponse } from 'next/server';
import { dbQuery } from '../../../lib/db';
import type { Review } from '../../../lib/types';

interface DBReviewDetailsRow {
  id: number;
  product_id: number;
  user_id: number;
  rating: number;
  comment: string | null;
  created_at: string | Date;
  user_name: string;
  user_email: string;
}

// POST: Submit a product review
export async function POST(request: NextRequest) {
  try {
    const { productId, userId, rating, comment } = await request.json();

    if (!productId || !userId || !rating) {
      return NextResponse.json({ error: 'Product ID, User ID, and Rating are required' }, { status: 400 });
    }

    const result = await dbQuery<{ id: number }>(
      'INSERT INTO reviews (product_id, user_id, rating, comment) VALUES ($1, $2, $3, $4) RETURNING id',
      [Number(productId), Number(userId), Number(rating), comment || null]
    );

    const newId = result[0].id;

    // Fetch the new review joined with user details
    const reviews = await dbQuery<DBReviewDetailsRow>(`
      SELECT r.id, r.product_id, r.user_id, r.rating, r.comment, r.created_at,
             u.name AS user_name, u.email AS user_email
      FROM reviews r
      JOIN users u ON r.user_id = u.id
      WHERE r.id = $1
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
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
