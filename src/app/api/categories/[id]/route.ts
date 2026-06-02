import { NextRequest, NextResponse } from 'next/server';
import { dbQuery } from '../../../../lib/db';

const slugify = (str: string) =>
  str
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '');

interface RouteParams {
  params: Promise<{ id: string }>;
}

// PUT: Update a category
export async function PUT(request: NextRequest, { params }: RouteParams) {
  try {
    const { id } = await params;
    const data = await request.json();
    if (!data.name) {
      return NextResponse.json({ error: 'Name is required' }, { status: 400 });
    }
    const slug = data.slug || slugify(data.name);

    await dbQuery(
      'UPDATE categories SET name = $1, slug = $2, image = $3 WHERE id = $4',
      [data.name, slug, data.image || null, Number(id)]
    );

    const productCountResult = await dbQuery<{ count: number }>(
      'SELECT CAST(COUNT(*) AS INTEGER) as count FROM products WHERE category_id = $1 AND is_active = true',
      [Number(id)]
    );
    const count = productCountResult[0]?.count || 0;

    return NextResponse.json({
      id: Number(id),
      name: data.name,
      slug,
      image: data.image || null,
      products_count: count
    });
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// DELETE: Remove a category
export async function DELETE(request: NextRequest, { params }: RouteParams) {
  try {
    const { id } = await params;

    // Check if products exist in category
    const productsResult = await dbQuery<{ count: number }>(
      'SELECT CAST(COUNT(*) AS INTEGER) as count FROM products WHERE category_id = $1',
      [Number(id)]
    );
    const count = productsResult[0]?.count || 0;
    if (count > 0) {
      return NextResponse.json(
        { error: 'Cannot delete category with existing products. Remove or reassign products first.' },
        { status: 400 }
      );
    }

    await dbQuery('DELETE FROM categories WHERE id = $1', [Number(id)]);
    return NextResponse.json({ success: true });
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
