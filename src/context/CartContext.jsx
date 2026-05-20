"use client";
import { createContext, useContext, useState, useEffect, useCallback } from 'react';
import { getDiscountedPrice } from '../utils/helpers';

const CartContext = createContext(null);

export function CartProvider({ children }) {
  const [items, setItems] = useState([]);
  const [toasts, setToasts] = useState([]);
  const [isLoaded, setIsLoaded] = useState(false);

  useEffect(() => {
    const saved = localStorage.getItem('7jc_cart');
    if (saved) {
      try {
        setItems(JSON.parse(saved));
      } catch (e) {
        console.error('Failed to parse cart items', e);
      }
    }
    setIsLoaded(true);
  }, []);

  useEffect(() => {
    if (isLoaded) {
      localStorage.setItem('7jc_cart', JSON.stringify(items));
    }
  }, [items, isLoaded]);

  const removeToast = useCallback((id) => {
    setToasts(prev => prev.filter(t => t.id !== id));
  }, []);

  const showToast = useCallback((message, type = 'success', duration = 3000) => {
    const id = Date.now() + Math.random();
    setToasts(prev => [...prev, { id, message, type, duration }]);
  }, []);

  const addItem = useCallback((product) => {
    const existing = items.find(i => i.product.id === product.id);
    if (existing) {
      showToast('This item is already in your cart.', 'info');
      return;
    }
    if (product.stock < 1) {
      showToast('Product is out of stock.', 'error');
      return;
    }
    setItems(prev => [...prev, { product, quantity: 1 }]);
    showToast('Product added to cart!');
  }, [items, showToast]);

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
    <CartContext.Provider value={{ items, addItem, removeItem, updateQuantity, clearCart, isInCart, itemCount, total, toasts, removeToast, showToast }}>
      {children}
    </CartContext.Provider>
  );
}

export const useCart = () => useContext(CartContext);
