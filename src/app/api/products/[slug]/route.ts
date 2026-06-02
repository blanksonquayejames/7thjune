import { NextRequest, NextResponse } from 'next/server';
import { dbQuery } from '../../../../lib/db';

const slugify = (str: string) =>
  str
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/(^-|-$)/g, '');

interface RouteParams {
  params: Promise<{ slug: string }>;
}

interface DBProductRow {
  id: number;
  category_id: number;
  name: string;
  slug: string;
  price: string | number;
  stock: number;
  description: string | null;
  image: string | null;
  is_active: boolean;
  is_hot: boolean;
  is_featured: boolean;
  discount_percentage: number;
  discount_start: string | null;
  discount_end: string | null;
  category_name: string;
  category_slug: string;
  category_image: string | null;
}

interface DBReviewRow {
  id: number;
  product_id: number;
  user_id: number;
  rating: number;
  comment: string | null;
  created_at: string | Date;
  user_name: string;
  user_email: string;
}

// GET: Fetch product detail by slug
export async function GET(request: NextRequest, { params }: RouteParams) {
  try {
    const { slug } = await params;

    // Find the product by slug
    const products = await dbQuery<DBProductRow>(`
      SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.image AS category_image
      FROM products p
      JOIN categories c ON p.category_id = c.id
      WHERE p.slug = $1 AND p.is_active = true
    `, [slug]);

    if (products.length === 0) {
      return NextResponse.json({ error: 'Product not found' }, { status: 404 });
    }

    const product = products[0];

    // Fetch reviews for this product
    const reviews = await dbQuery<DBReviewRow>(`
      SELECT r.id, r.product_id, r.user_id, r.rating, r.comment, r.created_at,
             u.name AS user_name, u.email AS user_email
      FROM reviews r
      JOIN users u ON r.user_id = u.id
      WHERE r.product_id = $1
      ORDER BY r.id DESC
    `, [product.id]);

    const formattedReviews = reviews.map(r => ({
      id: r.id,
      product_id: r.product_id,
      user_id: r.user_id,
      rating: r.rating,
      comment: r.comment,
      created_at: r.created_at,
      user: {
        id: r.user_id,
        name: r.user_name,
        email: r.user_email
      }
    }));

    // Fetch related products (same category, different id)
    const relatedProducts = await dbQuery<DBProductRow>(`
      SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.image AS category_image
      FROM products p
      JOIN categories c ON p.category_id = c.id
      WHERE p.category_id = $1 AND p.id != $2 AND p.is_active = true
      LIMIT 4
    `, [product.category_id, product.id]);

    const formattedRelated = relatedProducts.map(p => ({
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

    return NextResponse.json({
      id: product.id,
      category_id: product.category_id,
      name: product.name,
      slug: product.slug,
      price: Number(product.price),
      stock: product.stock,
      description: product.description,
      image: product.image,
      is_active: Boolean(product.is_active),
      is_hot: Boolean(product.is_hot),
      is_featured: Boolean(product.is_featured),
      discount_percentage: product.discount_percentage,
      discount_start: product.discount_start,
      discount_end: product.discount_end,
      category: {
        id: product.category_id,
        name: product.category_name,
        slug: product.category_slug,
        image: product.category_image
      },
      reviews: formattedReviews,
      relatedProducts: formattedRelated
    });
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// PUT: Update product by ID (Admin)
export async function PUT(request: NextRequest, { params }: RouteParams) {
  try {
    const { slug: idString } = await params;
    const id = Number(idString);
    const data = await request.json();

    // Fetch the existing product to merge fields
    const products = await dbQuery<DBProductRow>('SELECT * FROM products WHERE id = $1', [id]);
    if (products.length === 0) {
      return NextResponse.json({ error: 'Product not found' }, { status: 404 });
    }
    const existing = products[0];

    // Merge incoming data with existing data
    const category_id = data.category_id !== undefined ? Number(data.category_id) : existing.category_id;
    const name = data.name !== undefined ? data.name : existing.name;
    const slug = data.name !== undefined ? slugify(data.name) : existing.slug;
    const price = data.price !== undefined ? Number(data.price) : Number(existing.price);
    const stock = data.stock !== undefined ? Number(data.stock) : existing.stock;
    const description = data.description !== undefined ? data.description : existing.description;
    const image = data.image !== undefined ? data.image : existing.image;
    const is_active = data.is_active !== undefined ? !!data.is_active : existing.is_active;
    const is_hot = data.is_hot !== undefined ? !!data.is_hot : existing.is_hot;
    const is_featured = data.is_featured !== undefined ? !!data.is_featured : existing.is_featured;
    const discount_percentage = data.discount_percentage !== undefined ? Number(data.discount_percentage) : existing.discount_percentage;
    const discount_start = data.discount_start !== undefined ? data.discount_start : existing.discount_start;
    const discount_end = data.discount_end !== undefined ? data.discount_end : existing.discount_end;

    await dbQuery(`
      UPDATE products 
      SET category_id = $1, name = $2, slug = $3, price = $4, stock = $5, 
          description = $6, image = $7, is_active = $8, is_hot = $9, is_featured = $10, 
          discount_percentage = $11, discount_start = $12, discount_end = $13
      WHERE id = $14
    `, [
      category_id, name, slug, price, stock,
      description, image, is_active, is_hot, is_featured,
      discount_percentage, discount_start, discount_end,
      id
    ]);

    // Fetch the updated product details joined with category info
    const updatedProducts = await dbQuery<DBProductRow>(`
      SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.image AS category_image
      FROM products p
      JOIN categories c ON p.category_id = c.id
      WHERE p.id = $1
    `, [id]);

    const updatedProduct = updatedProducts[0];

    return NextResponse.json({
      id: updatedProduct.id,
      category_id: updatedProduct.category_id,
      name: updatedProduct.name,
      slug: updatedProduct.slug,
      price: Number(updatedProduct.price),
      stock: updatedProduct.stock,
      description: updatedProduct.description,
      image: updatedProduct.image,
      is_active: Boolean(updatedProduct.is_active),
      is_hot: Boolean(updatedProduct.is_hot),
      is_featured: Boolean(updatedProduct.is_featured),
      discount_percentage: updatedProduct.discount_percentage,
      discount_start: updatedProduct.discount_start,
      discount_end: updatedProduct.discount_end,
      category: {
        id: updatedProduct.category_id,
        name: updatedProduct.category_name,
        slug: updatedProduct.category_slug,
        image: updatedProduct.category_image
      }
    });
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}

// DELETE: Remove product by ID (Admin)
export async function DELETE(request: NextRequest, { params }: RouteParams) {
  try {
    const { slug: idString } = await params;
    const id = Number(idString);

    await dbQuery('DELETE FROM products WHERE id = $1', [id]);
    return NextResponse.json({ success: true });
  } catch (error: any) {
    return NextResponse.json({ error: error.message }, { status: 500 });
  }
}
