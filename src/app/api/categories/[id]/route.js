import { NextResponse } from 'next/server';
import { dbQuery } from '../../../../lib/db';

const slugify = (str) => str.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');

// PUT: Update a category
export async function PUT(request, { params }) {
  try {
    const { id } = await params;
    const data = await request.json();
    if (!data.name) {
      return NextResponse.json({ error: 'Name is required' }, { status: 400 });
    }
    const slug = data.slug || slugify(data.name);
    
    await dbQuery(
      'UPDATE categories SET name = ?, slug = ?, image = ? WHERE id = ?',
      [data.name, slug, data.image || null, id]
    );

    const productCountResult = await dbQuery(
      'SELECT COUNT(*) as count FROM products WHERE category_id = ? AND is_active = 1',
      [id]
    );
    const count = productCountResult[0]?.count || 0;

    return NextResponse.json({
      id: Number(id),
      name: data.name,
      slug,
      image: data.image || null,
      products_count: count
    });
  } catch (error) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// DELETE: Remove a category
export async function DELETE(request, { params }) {
  try {
    const { id } = await params;

    // Check if products exist in category
    const productsResult = await dbQuery(
      'SELECT COUNT(*) as count FROM products WHERE category_id = ?',
      [id]
    );
    const count = productsResult[0]?.count || 0;
    if (count > 0) {
      return NextResponse.json(
        { error: 'Cannot delete category with existing products. Remove or reassign products first.' },
        { status: 400 }
      );
    }

    await dbQuery('DELETE FROM categories WHERE id = ?', [id]);
    return NextResponse.json({ success: true });
  } catch (error) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
