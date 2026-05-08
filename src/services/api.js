import db from '../../db.json';

// Simulate async API calls using the local db.json
const delay = (ms = 200) => new Promise(r => setTimeout(r, ms));

// ── Categories ──
export const getCategories = async () => {
  await delay();
  return db.categories.map(cat => ({
    ...cat,
    products_count: db.products.filter(p => p.category_id === cat.id && p.is_active).length
  }));
};

// ── Products ──
export const getProducts = async ({ category, search, sort } = {}) => {
  await delay();
  let products = db.products.filter(p => p.is_active);

  if (category) {
    const cat = db.categories.find(c => c.slug === category);
    if (cat) products = products.filter(p => p.category_id === cat.id);
  }

  if (search) {
    const q = search.toLowerCase();
    products = products.filter(p =>
      p.name.toLowerCase().includes(q) || p.description.toLowerCase().includes(q)
    );
  }

  switch (sort) {
    case 'price_low': products.sort((a, b) => a.price - b.price); break;
    case 'price_high': products.sort((a, b) => b.price - a.price); break;
    case 'name': products.sort((a, b) => a.name.localeCompare(b.name)); break;
    default: products.sort((a, b) => b.id - a.id); break;
  }

  return products;
};

export const getProductBySlug = async (slug) => {
  await delay();
  const product = db.products.find(p => p.slug === slug && p.is_active);
  if (!product) return null;

  const category = db.categories.find(c => c.id === product.category_id);
  const reviews = db.reviews
    .filter(r => r.product_id === product.id)
    .map(r => ({ ...r, user: db.users.find(u => u.id === r.user_id) }));

  const relatedProducts = db.products
    .filter(p => p.category_id === product.category_id && p.id !== product.id && p.is_active)
    .slice(0, 4);

  return { ...product, category, reviews, relatedProducts };
};

export const getFeaturedProducts = async (limit = 8) => {
  await delay();
  return db.products.filter(p => p.is_active).sort((a, b) => b.id - a.id).slice(0, limit);
};

export const getNewArrivalsByCategory = async () => {
  await delay();
  const cats = db.categories.slice(0, 4);
  const result = {};
  cats.forEach(cat => {
    result[cat.slug] = {
      name: cat.name,
      products: db.products
        .filter(p => p.is_active && p.category_id === cat.id)
        .sort((a, b) => b.id - a.id)
        .slice(0, 6),
    };
  });
  return result;
};

// ── Auth ──
export const loginUser = async (email, password) => {
  await delay(300);
  const user = db.users.find(u => u.email === email && u.password === password);
  if (!user) throw new Error('Invalid email or password');
  const { password: _, ...safeUser } = user;
  return safeUser;
};

export const registerUser = async (name, email, password) => {
  await delay(300);
  if (db.users.find(u => u.email === email)) throw new Error('Email already exists');
  const newUser = { id: db.users.length + 1, name, email, role: 'customer' };
  return newUser;
};

// ── Orders ──
export const getUserOrders = async (userId) => {
  await delay();
  return db.orders
    .filter(o => o.user_id === userId)
    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
    .map(order => ({
      ...order,
      items: order.items.map(item => ({
        ...item,
        product: db.products.find(p => p.id === item.product_id)
      }))
    }));
};

export const getOrderById = async (userId, orderId) => {
  await delay();
  const order = db.orders.find(o => o.id === Number(orderId) && o.user_id === userId);
  if (!order) return null;
  return {
    ...order,
    items: order.items.map(item => ({
      ...item,
      product: db.products.find(p => p.id === item.product_id)
    }))
  };
};

export const createOrder = async (orderData) => {
  await delay(400);
  const newOrder = {
    id: db.orders.length + 1,
    ...orderData,
    status: 'pending',
    created_at: new Date().toISOString(),
  };
  db.orders.push(newOrder);
  return newOrder;
};

// ── Reviews ──
export const submitReview = async (productId, userId, rating, comment) => {
  await delay(300);
  const review = {
    id: db.reviews.length + 1,
    product_id: productId,
    user_id: userId,
    rating,
    comment,
  };
  db.reviews.push(review);
  return { ...review, user: db.users.find(u => u.id === userId) };
};

// ── Admin ──
export const getAllOrders = async () => {
  await delay();
  return db.orders.map(order => ({
    ...order,
    user: db.users.find(u => u.id === order.user_id),
    items: order.items.map(item => ({
      ...item,
      product: db.products.find(p => p.id === item.product_id)
    }))
  }));
};

export const getAllUsers = async () => {
  await delay();
  return db.users.map(({ password, ...user }) => user);
};

export const updateOrderStatus = async (orderId, status) => {
  await delay(300);
  const order = db.orders.find(o => o.id === orderId);
  if (order) order.status = status;
  return order;
};

export const getAllProducts = async () => {
  await delay();
  return db.products.map(p => ({
    ...p,
    category: db.categories.find(c => c.id === p.category_id)
  }));
};

// ── Admin Product CRUD ──
const slugify = (str) => str.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');

export const createProduct = async (data) => {
  await delay(300);
  const newId = db.products.length > 0 ? Math.max(...db.products.map(p => p.id)) + 1 : 1;
  const product = {
    id: newId,
    category_id: Number(data.category_id),
    name: data.name,
    slug: slugify(data.name),
    price: Number(data.price),
    stock: Number(data.stock),
    description: data.description || '',
    image: data.image || null,
    is_active: data.is_active !== false,
    is_hot: data.is_hot || false,
    is_featured: data.is_featured || false,
    discount_percentage: Number(data.discount_percentage) || 0,
    discount_start: data.discount_start || null,
    discount_end: data.discount_end || null,
  };
  db.products.push(product);
  return { ...product, category: db.categories.find(c => c.id === product.category_id) };
};

export const updateProduct = async (id, data) => {
  await delay(300);
  const idx = db.products.findIndex(p => p.id === id);
  if (idx === -1) throw new Error('Product not found');
  const updated = {
    ...db.products[idx],
    category_id: Number(data.category_id),
    name: data.name,
    slug: slugify(data.name),
    price: Number(data.price),
    stock: Number(data.stock),
    description: data.description || '',
    image: data.image || db.products[idx].image,
    is_active: data.is_active !== false,
    is_hot: data.is_hot || false,
    is_featured: data.is_featured || false,
    discount_percentage: Number(data.discount_percentage) || 0,
    discount_start: data.discount_start || null,
    discount_end: data.discount_end || null,
  };
  db.products[idx] = updated;
  return { ...updated, category: db.categories.find(c => c.id === updated.category_id) };
};

export const deleteProduct = async (id) => {
  await delay(300);
  const idx = db.products.findIndex(p => p.id === id);
  if (idx === -1) throw new Error('Product not found');
  db.products.splice(idx, 1);
  return true;
};

export const updateProductDiscount = async (id, discountData) => {
  await delay(300);
  const idx = db.products.findIndex(p => p.id === id);
  if (idx === -1) throw new Error('Product not found');
  db.products[idx] = {
    ...db.products[idx],
    discount_percentage: Number(discountData.discount_percentage) || 0,
    discount_start: discountData.discount_start || null,
    discount_end: discountData.discount_end || null,
  };
  return { ...db.products[idx], category: db.categories.find(c => c.id === db.products[idx].category_id) };
};

// ── Admin Category CRUD ──
export const createCategory = async (data) => {
  await delay(300);
  const newId = db.categories.length > 0 ? Math.max(...db.categories.map(c => c.id)) + 1 : 1;
  const category = {
    id: newId,
    name: data.name,
    slug: slugify(data.name),
    image: data.image || null,
  };
  db.categories.push(category);
  return { ...category, products_count: 0 };
};

export const updateCategory = async (id, data) => {
  await delay(300);
  const idx = db.categories.findIndex(c => c.id === id);
  if (idx === -1) throw new Error('Category not found');
  db.categories[idx] = {
    ...db.categories[idx],
    name: data.name,
    slug: slugify(data.name),
    image: data.image || db.categories[idx].image,
  };
  const cat = db.categories[idx];
  return { ...cat, products_count: db.products.filter(p => p.category_id === cat.id && p.is_active).length };
};

export const deleteCategory = async (id) => {
  await delay(300);
  const hasProducts = db.products.some(p => p.category_id === id);
  if (hasProducts) throw new Error('Cannot delete category with existing products. Remove or reassign products first.');
  const idx = db.categories.findIndex(c => c.id === id);
  if (idx === -1) throw new Error('Category not found');
  db.categories.splice(idx, 1);
  return true;
};
