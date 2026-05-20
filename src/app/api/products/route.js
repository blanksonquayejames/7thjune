import { NextResponse } from 'next/server';
import { dbQuery } from '../../../lib/db';

const slugify = (str) => str.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');

// GET: Fetch products with filters
export async function GET(request) {
  try {
    const { searchParams } = request.nextUrl;
    const category = searchParams.get('category');
    const search = searchParams.get('search');
    const sort = searchParams.get('sort');
    const all = searchParams.get('all') === 'true'; // If true, include inactive products (admin mode)

    let query = `
      SELECT p.*, 
             c.name AS category_name, c.slug AS category_slug, c.image AS category_image
      FROM products p
      JOIN categories c ON p.category_id = c.id
      WHERE 1=1
    `;
    const params = [];

    if (!all) {
      query += ' AND p.is_active = 1';
    }

    if (category) {
      query += ' AND c.slug = ?';
      params.push(category);
    }

    if (search) {
      query += ' AND (p.name LIKE ? OR p.description LIKE ?)';
      const searchTerm = `%${search}%`;
      params.push(searchTerm, searchTerm);
    }

    switch (sort) {
      case 'price_low':
        query += ' ORDER BY p.price ASC';
        break;
      case 'price_high':
        query += ' ORDER BY p.price DESC';
        break;
      case 'name':
        query += ' ORDER BY p.name ASC';
        break;
      default:
        query += ' ORDER BY p.id DESC';
        break;
    }

    const products = await dbQuery(query, params);

    // Format matches standard API representation
    const formattedProducts = products.map(p => ({
      id: p.id,
      category_id: p.category_id,
      name: p.name,
      slug: p.slug,
      price: Number(p.price),
      stock: p.stock,
      description: p.description,
      image: p.image,
      is_active: Boolean(p.is_active),
      is_hot: Boolean(p.is_hot),
      is_featured: Boolean(p.is_featured),
      discount_percentage: p.discount_percentage,
      discount_start: p.discount_start,
      discount_end: p.discount_end,
      category: {
        id: p.category_id,
        name: p.category_name,
        slug: p.category_slug,
        image: p.category_image
      }
    }));

    return NextResponse.json(formattedProducts);
  } catch (error) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// POST: Add a new product (Admin)
export async function POST(request) {
  try {
    const data = await request.json();
    if (!data.name || !data.category_id || data.price === undefined || data.stock === undefined) {
      return NextResponse.json({ error: 'Missing required product fields' }, { status: 400 });
    }

    const slug = slugify(data.name);
    const isActive = data.is_active !== false ? 1 : 0;
    const isHot = data.is_hot ? 1 : 0;
    const isFeatured = data.is_featured ? 1 : 0;

    const result = await dbQuery(`
      INSERT INTO products (
        category_id, name, slug, price, stock, description, image, 
        is_active, is_hot, is_featured, discount_percentage, discount_start, discount_end
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `, [
      Number(data.category_id),
      data.name,
      slug,
      Number(data.price),
      Number(data.stock),
      data.description || '',
      data.image || null,
      isActive,
      isHot,
      isFeatured,
      Number(data.discount_percentage) || 0,
      data.discount_start || null,
      data.discount_end || null
    ]);

    const newId = result.insertId;

    // Fetch the newly created product details with category info
    const [newProduct] = await dbQuery(`
      SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.image AS category_image
      FROM products p
      JOIN categories c ON p.category_id = c.id
      WHERE p.id = ?
    `, [newId]);

    return NextResponse.json({
      id: newProduct.id,
      category_id: newProduct.category_id,
      name: newProduct.name,
      slug: newProduct.slug,
      price: Number(newProduct.price),
      stock: newProduct.stock,
      description: newProduct.description,
      image: newProduct.image,
      is_active: Boolean(newProduct.is_active),
      is_hot: Boolean(newProduct.is_hot),
      is_featured: Boolean(newProduct.is_featured),
      discount_percentage: newProduct.discount_percentage,
      discount_start: newProduct.discount_start,
      discount_end: newProduct.discount_end,
      category: {
        id: newProduct.category_id,
        name: newProduct.category_name,
        slug: newProduct.category_slug,
        image: newProduct.category_image
      }
    });
  } catch (error) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
