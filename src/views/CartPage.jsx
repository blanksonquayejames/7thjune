import { useEffect } from 'react';
import Link from 'next/link';
import { BsTrash, BsPlus, BsDash, BsCart4, BsChevronRight } from 'react-icons/bs';
import { useCart } from '../context/CartContext';
import { useAuth } from '../context/AuthContext';
import { useConfirm } from '../context/ConfirmationContext';
import { formatPrice, getDiscountedPrice } from '../utils/helpers';
import './CartPage.css';

export default function CartPage() {
  const { items, removeItem, updateQuantity, total, clearCart } = useCart();
  const { user } = useAuth();
  const confirm = useConfirm();

  const handleClearCart = async () => {
    const hasConfirmed = await confirm({
      title: 'Clear Cart',
      message: 'Are you sure you want to remove all items from your cart?',
      confirmText: 'Clear All',
      cancelText: 'Keep Items',
      type: 'danger'
    });
    if (hasConfirmed) {
      clearCart();
    }
  };

  const handleRemoveItem = async (productId, productName) => {
    const hasConfirmed = await confirm({
      title: 'Remove Item',
      message: `Are you sure you want to remove "${productName}" from your cart?`,
      confirmText: 'Remove',
      cancelText: 'Cancel',
      type: 'danger'
    });
    if (hasConfirmed) {
      removeItem(productId);
    }
  };

  useEffect(() => {
    document.title = 'Cart - 7th June Computers';
  }, []);

  if (items.length === 0) {
    return (
      <div className="empty-state">
        <div className="empty-state__icon"><BsCart4 /></div>
        <h3 className="empty-state__title">Your cart is empty</h3>
        <p className="empty-state__text">Add some products to get started</p>
        <Link href="/products" className="btn btn--primary">Browse Products</Link>
      </div>
    );
  }

  return (
    <>
      <div className="page-header">
        <div className="container">
          <h1 className="page-header__title">Shopping Cart</h1>
          <div className="page-header__breadcrumb">
            <Link href="/">Home</Link><BsChevronRight style={{ fontSize: '.7rem' }} /><span>Cart</span>
          </div>
        </div>
      </div>
      <section className="section">
        <div className="container">
          <div className="cart-layout">
            <div className="cart-items">
              {items.map(({ product, quantity }) => (
                <div key={product.id} className="cart-item" id={`cart-item-${product.id}`}>
                  <div className="cart-item__image" style={{ backgroundColor: '#f0f9ff' }}>
                    {product.image ? <img src={product.image} alt={product.name} /> : <span style={{ fontSize: '2rem' }}>📦</span>}
                  </div>
                  <div className="cart-item__info">
                    <Link href={`/products/${product.slug}`} className="cart-item__name">{product.name}</Link>
                    <span className="cart-item__price">{formatPrice(getDiscountedPrice(product))}</span>
                  </div>
                  <div className="cart-item__actions">
                    <div className="cart-item__qty">
                      <button className="btn btn--icon-sm btn--outline" onClick={() => updateQuantity(product.id, quantity - 1)} disabled={quantity <= 1}><BsDash /></button>
                      <span className="cart-item__qty-val">{quantity}</span>
                      <button className="btn btn--icon-sm btn--outline" onClick={() => updateQuantity(product.id, quantity + 1)} disabled={quantity >= product.stock}><BsPlus /></button>
                    </div>
                    <span className="cart-item__subtotal">{formatPrice(getDiscountedPrice(product) * quantity)}</span>
                    <button className="btn btn--icon-sm btn--ghost" onClick={() => handleRemoveItem(product.id, product.name)} title="Remove"><BsTrash style={{ color: 'var(--danger)' }} /></button>
                  </div>
                </div>
              ))}
              <div style={{ display: 'flex', justifyContent: 'space-between', marginTop: '24px', alignItems: 'center', flexWrap: 'wrap', gap: '16px' }}>
                <Link href="/products" className="btn btn--outline" style={{ display: 'inline-flex', alignItems: 'center' }}>
                  &larr; Continue Shopping
                </Link>
                <button className="btn btn--ghost" style={{ color: 'var(--danger)', fontWeight: 600, display: 'inline-flex', alignItems: 'center', gap: '8px' }} onClick={handleClearCart}>
                  <BsTrash /> Clear Cart
                </button>
              </div>
            </div>
            <div className="cart-summary">
              <h3 className="cart-summary__title">Order Summary</h3>
              <div className="cart-summary__row"><span>Subtotal</span><span>{formatPrice(total)}</span></div>
              <div className="cart-summary__row"><span>Tax (7.5%)</span><span>{formatPrice(total * 0.075)}</span></div>
              <div className="cart-summary__divider" />
              <div className="cart-summary__row cart-summary__row--total"><span>Total</span><span>{formatPrice(total + total * 0.075)}</span></div>
              {user ? (
                <Link href="/checkout" className="btn btn--accent btn--lg" style={{ width: '100%', marginTop: '16px' }} id="checkout-btn">Proceed to Checkout</Link>
              ) : (
                <Link href="/login?redirect=/checkout" className="btn btn--primary btn--lg" style={{ width: '100%', marginTop: '16px' }}>Sign in to Checkout</Link>
              )}
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
