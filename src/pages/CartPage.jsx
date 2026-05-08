import { Link } from 'react-router-dom';
import { BsTrash, BsPlus, BsDash, BsCart4, BsChevronRight } from 'react-icons/bs';
import { useCart } from '../context/CartContext';
import { useAuth } from '../context/AuthContext';
import { formatPrice, getDiscountedPrice } from '../utils/helpers';
import './CartPage.css';

export default function CartPage() {
  const { items, removeItem, updateQuantity, total } = useCart();
  const { user } = useAuth();

  document.title = 'Cart - 7th June Computers';

  if (items.length === 0) {
    return (
      <div className="empty-state">
        <div className="empty-state__icon"><BsCart4 /></div>
        <h3 className="empty-state__title">Your cart is empty</h3>
        <p className="empty-state__text">Add some products to get started</p>
        <Link to="/products" className="btn btn--primary">Browse Products</Link>
      </div>
    );
  }

  return (
    <>
      <div className="page-header">
        <div className="container">
          <h1 className="page-header__title">Shopping Cart</h1>
          <div className="page-header__breadcrumb">
            <Link to="/">Home</Link><BsChevronRight style={{ fontSize: '.7rem' }} /><span>Cart</span>
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
                    <Link to={`/products/${product.slug}`} className="cart-item__name">{product.name}</Link>
                    <span className="cart-item__price">{formatPrice(getDiscountedPrice(product))}</span>
                  </div>
                  <div className="cart-item__qty">
                    <button className="btn btn--icon-sm btn--outline" onClick={() => updateQuantity(product.id, quantity - 1)} disabled={quantity <= 1}><BsDash /></button>
                    <span className="cart-item__qty-val">{quantity}</span>
                    <button className="btn btn--icon-sm btn--outline" onClick={() => updateQuantity(product.id, quantity + 1)} disabled={quantity >= product.stock}><BsPlus /></button>
                  </div>
                  <span className="cart-item__subtotal">{formatPrice(getDiscountedPrice(product) * quantity)}</span>
                  <button className="btn btn--icon-sm btn--ghost" onClick={() => removeItem(product.id)} title="Remove"><BsTrash style={{ color: 'var(--danger)' }} /></button>
                </div>
              ))}
            </div>
            <div className="cart-summary">
              <h3 className="cart-summary__title">Order Summary</h3>
              <div className="cart-summary__row"><span>Subtotal</span><span>{formatPrice(total)}</span></div>
              <div className="cart-summary__row"><span>Tax (7.5%)</span><span>{formatPrice(total * 0.075)}</span></div>
              <div className="cart-summary__divider" />
              <div className="cart-summary__row cart-summary__row--total"><span>Total</span><span>{formatPrice(total + total * 0.075)}</span></div>
              {user ? (
                <Link to="/checkout" className="btn btn--accent btn--lg" style={{ width: '100%', marginTop: '16px' }} id="checkout-btn">Proceed to Checkout</Link>
              ) : (
                <Link to="/login?redirect=/checkout" className="btn btn--primary btn--lg" style={{ width: '100%', marginTop: '16px' }}>Sign in to Checkout</Link>
              )}
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
