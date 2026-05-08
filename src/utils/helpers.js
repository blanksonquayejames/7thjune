export const formatPrice = (price) => {
  return `₵${Number(price).toFixed(2)}`;
};

export const hasActiveDiscount = (product) => {
  if (!product.discount_percentage || product.discount_percentage <= 0) return false;
  const now = new Date();
  if (product.discount_start && now < new Date(product.discount_start)) return false;
  if (product.discount_end && now > new Date(product.discount_end)) return false;
  return true;
};

export const getDiscountedPrice = (product) => {
  if (!hasActiveDiscount(product)) return Number(product.price);
  return Math.round(Number(product.price) * (1 - product.discount_percentage / 100) * 100) / 100;
};

export const getSavings = (product) => {
  return Math.round((Number(product.price) - getDiscountedPrice(product)) * 100) / 100;
};

export const PASTEL_COLORS = ['#f0fdf4', '#f0f9ff', '#fffbeb', '#fdf4ff', '#eff6ff', '#f8fafc'];

export const getProductBgColor = (id) => PASTEL_COLORS[id % PASTEL_COLORS.length];

export const CATEGORY_ICONS = {
  'computers': 'BsCpu',
  'storage-components': 'BsHdd',
  'networking': 'BsWifi',
  'tablets': 'BsTablet',
  'peripherals': 'BsMouse',
};

export const getStatusBadgeClass = (status) => {
  const map = {
    pending: 'badge--pending',
    processing: 'badge--processing',
    shipped: 'badge--shipped',
    delivered: 'badge--delivered',
    cancelled: 'badge--cancelled',
  };
  return map[status] || 'badge--pending';
};
