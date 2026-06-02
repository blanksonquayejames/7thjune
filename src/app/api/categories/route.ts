import { NextRequest, NextResponse } from 'next/server';
import { dbQuery } from '../../../lib/db';
import type { Category } from '../../../lib/types';

const slugify = (str: string) =>
  str
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '');

// GET: Fetch all categories with products_count
export async function GET() {
  try {
    const categories = await dbQuery<Category>(`
      SELECT c.*, CAST(COUNT(p.id) AS INTEGER) AS products_count
      FROM categories c
      LEFT JOIN products p ON p.category_id = c.id AND p.is_active = true
      GROUP BY c.id
      ORDER BY c.name ASC
    `);
    return NextResponse.json(categories);
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// POST: Create a category (Admin)
export async function POST(request: NextRequest) {
  try {
    const data = await request.json();
    if (!data.name) {
      return NextResponse.json({ error: 'Name is required' }, { status: 400 });
    }
    const slug = data.slug || slugify(data.name);
    const result = await dbQuery<{ id: number }>(
      'INSERT INTO categories (name, slug, image) VALUES ($1, $2, $3) RETURNING id',
      [data.name, slug, data.image || null]
    );
    const newId = result[0].id;
    return NextResponse.json({
      id: newId,
      name: data.name,
      slug,
      image: data.image || null,
      products_count: 0
    });
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
