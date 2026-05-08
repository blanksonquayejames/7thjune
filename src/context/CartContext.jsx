import { createContext, useContext, useState, useEffect, useCallback } from 'react';
import { getDiscountedPrice } from '../utils/helpers';

const CartContext = createContext(null);

export function CartProvider({ children }) {
  const [items, setItems] = useState(() => {
    const saved = localStorage.getItem('7jc_cart');
    return saved ? JSON.parse(saved) : [];
  });
  const [toast, setToast] = useState(null);

  useEffect(() => {
    localStorage.setItem('7jc_cart', JSON.stringify(items));
  }, [items]);

  const showToast = useCallback((message, type = 'success') => {
    setToast({ message, type });
    setTimeout(() => setToast(null), 3000);
  }, []);

  const addItem = useCallback((product) => {
    setItems(prev => {
      const existing = prev.find(i => i.product.id === product.id);
      if (existing) {
        if (existing.quantity >= product.stock) {
          showToast('Not enough stock available.', 'error');
          return prev;
        }
        showToast('Cart updated!');
        return prev.map(i =>
          i.product.id === product.id ? { ...i, quantity: i.quantity + 1 } : i
        );
      }
      if (product.stock < 1) {
        showToast('Product is out of stock.', 'error');
        return prev;
      }
      showToast('Product added to cart!');
      return [...prev, { product, quantity: 1 }];
    });
  }, [showToast]);

  const removeItem = useCallback((productId) => {
    setItems(prev => prev.filter(i => i.product.id !== productId));
    showToast('Item removed from cart.');
  }, [showToast]);

  const updateQuantity = useCallback((productId, quantity) => {
    if (quantity < 1) return;
    setItems(prev => prev.map(i =>
      i.product.id === productId ? { ...i, quantity } : i
    ));
  }, []);

  const clearCart = useCallback(() => {
    setItems([]);
  }, []);

  const isInCart = useCallback((productId) => {
    return items.some(i => i.product.id === productId);
  }, [items]);

  const itemCount = items.reduce((sum, i) => sum + i.quantity, 0);
  const total = items.reduce((sum, i) => sum + getDiscountedPrice(i.product) * i.quantity, 0);

  return (
    <CartContext.Provider value={{ items, addItem, removeItem, updateQuantity, clearCart, isInCart, itemCount, total, toast, showToast }}>
      {children}
    </CartContext.Provider>
  );
}

export const useCart = () => useContext(CartContext);
