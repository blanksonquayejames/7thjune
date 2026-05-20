import { NextResponse } from 'next/server';
import { dbQuery } from '../../../lib/db';

const slugify = (str) => str.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');

// GET: Fetch all categories with products_count
export async function GET() {
  try {
    const categories = await dbQuery(`
      SELECT c.*, COUNT(p.id) AS products_count
      FROM categories c
      LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1
      GROUP BY c.id
      ORDER BY c.name ASC
    `);
    return NextResponse.json(categories);
  } catch (error) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// POST: Create a category (Admin)
export async function POST(request) {
  try {
    const data = await request.json();
    if (!data.name) {
      return NextResponse.json({ error: 'Name is required' }, { status: 400 });
    }
    const slug = data.slug || slugify(data.name);
    const result = await dbQuery(
      'INSERT INTO categories (name, slug, image) VALUES (?, ?, ?)',
      [data.name, slug, data.image || null]
    );
    const newId = result.insertId;
    return NextResponse.json({
      id: newId,
      name: data.name,
      slug,
      image: data.image || null,
      products_count: 0
    });
  } catch (error) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
