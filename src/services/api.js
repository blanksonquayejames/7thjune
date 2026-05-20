// ── Categories ──
export const getCategories = async () => {
  const res = await fetch('/api/categories');
  if (!res.ok) throw new Error('Failed to fetch categories');
  return res.json();
};

// ── Products ──
export const getProducts = async ({ category, search, sort } = {}) => {
  const params = new URLSearchParams();
  if (category) params.append('category', category);
  if (search) params.append('search', search);
  if (sort) params.append('sort', sort);

  const res = await fetch(`/api/products?${params.toString()}`);
  if (!res.ok) throw new Error('Failed to fetch products');
  return res.json();
};

export const getProductBySlug = async (slug) => {
  const res = await fetch(`/api/products/${slug}`);
  if (!res.ok) {
    if (res.status === 404) return null;
    throw new Error('Failed to fetch product details');
  }
  return res.json();
};

export const getFeaturedProducts = async (limit = 8) => {
  const products = await getProducts();
  return products.slice(0, limit);
};

export const getNewArrivalsByCategory = async () => {
  const categories = await getCategories();
  const cats = categories.slice(0, 4);
  const result = {};
  await Promise.all(
    cats.map(async (cat) => {
      try {
        const products = await getProducts({ category: cat.slug });
        result[cat.slug] = {
          name: cat.name,
          products: products.slice(0, 6)
        };
      } catch (e) {
        console.error(`Failed to fetch products for category: ${cat.slug}`, e);
      }
    })
  );
  return result;
};

// ── Auth ──
export const loginUser = async (email, password) => {
  const res = await fetch('/api/auth/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
  });
  if (!res.ok) {
    const err = await res.json();
    throw new Error(err.error || 'Invalid email or password');
  }
  return res.json();
};

export const registerUser = async (name, email, password) => {
  const res = await fetch('/api/auth/register', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name, email, password })
  });
  if (!res.ok) {
    const err = await res.json();
    throw new Error(err.error || 'Failed to register');
  }
  return res.json();
};

// ── Orders ──
export const getUserOrders = async (userId) => {
  const res = await fetch(`/api/orders?userId=${userId}`);
  if (!res.ok) throw new Error('Failed to fetch user orders');
  return res.json();
};

export const getOrderById = async (userId, orderId) => {
  const res = await fetch(`/api/orders/${orderId}?userId=${userId}`);
  if (!res.ok) {
    if (res.status === 404) return null;
    throw new Error('Failed to fetch order details');
  }
  return res.json();
};

export const createOrder = async (orderData) => {
  const res = await fetch('/api/orders', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(orderData)
  });
  if (!res.ok) {
    const err = await res.json();
    throw new Error(err.error || 'Failed to create order');
  }
  return res.json();
};

// ── Reviews ──
export const submitReview = async (productId, userId, rating, comment) => {
  const res = await fetch('/api/reviews', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ productId, userId, rating, comment })
  });
  if (!res.ok) {
    const err = await res.json();
    throw new Error(err.error || 'Failed to submit review');
  }
  return res.json();
};

// ── Admin ──
export const getAllOrders = async () => {
  const res = await fetch('/api/orders?all=true');
  if (!res.ok) throw new Error('Failed to fetch all orders');
  return res.json();
};

export const getAllUsers = async () => {
  const res = await fetch('/api/admin/users');
  if (!res.ok) throw new Error('Failed to fetch users');
  return res.json();
};

export const updateOrderStatus = async (orderId, status) => {
  const res = await fetch(`/api/orders/${orderId}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ status })
  });
  if (!res.ok) throw new Error('Failed to update order status');
  return res.json();
};

export const getAllProducts = async () => {
  const res = await fetch('/api/products?all=true');
  if (!res.ok) throw new Error('Failed to fetch all products');
  return res.json();
};

// ── Admin Product CRUD ──
export const createProduct = async (data) => {
  const res = await fetch('/api/products', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  if (!res.ok) {
    const err = await res.json();
    throw new Error(err.error || 'Failed to create product');
  }
  return res.json();
};

export const updateProduct = async (id, data) => {
  const res = await fetch(`/api/products/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  if (!res.ok) {
    const err = await res.json();
    throw new Error(err.error || 'Failed to update product');
  }
  return res.json();
};

export const deleteProduct = async (id) => {
  const res = await fetch(`/api/products/${id}`, {
    method: 'DELETE'
  });
  if (!res.ok) throw new Error('Failed to delete product');
  return true;
};

export const updateProductDiscount = async (id, discountData) => {
  const res = await fetch(`/api/products/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(discountData)
  });
  if (!res.ok) throw new Error('Failed to update product discount');
  return res.json();
};

// ── Admin Category CRUD ──
export const createCategory = async (data) => {
  const res = await fetch('/api/categories', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  if (!res.ok) {
    const err = await res.json();
    throw new Error(err.error || 'Failed to create category');
  }
  return res.json();
};

export const updateCategory = async (id, data) => {
  const res = await fetch(`/api/categories/${id}`, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data)
  });
  if (!res.ok) {
    const err = await res.json();
    throw new Error(err.error || 'Failed to update category');
  }
  return res.json();
};

export const deleteCategory = async (id) => {
  const res = await fetch(`/api/categories/${id}`, {
    method: 'DELETE'
  });
  if (!res.ok) {
    const err = await res.json();
    throw new Error(err.error || 'Failed to delete category');
  }
  return true;
};
