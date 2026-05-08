import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { BsChevronRight } from 'react-icons/bs';
import { useCart } from '../context/CartContext';
import { useAuth } from '../context/AuthContext';
import { createOrder } from '../services/api';
import { formatPrice, getDiscountedPrice } from '../utils/helpers';
import './CheckoutPage.css';

export default function CheckoutPage() {
  const { items, total, clearCart, showToast } = useCart();
  const { user } = useAuth();
  const navigate = useNavigate();
  const [form, setForm] = useState({ street: '', city: '', region: '', country: 'Ghana', phone: '', shipping_method: 'standard', payment_method: 'paystack' });
  const [loading, setLoading] = useState(false);
  const [step, setStep] = useState(1);

  document.title = 'Checkout - 7th June Computers';

  if (items.length === 0) {
    return <div className="empty-state"><div className="empty-state__icon">🛒</div><h3 className="empty-state__title">Cart is empty</h3><Link to="/products" className="btn btn--primary">Browse Products</Link></div>;
  }

  const tax = total * 0.075;
  const grandTotal = total + tax;

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const orderItems = items.map(i => ({ product_id: i.product.id, quantity: i.quantity, price: getDiscountedPrice(i.product) }));
      const address = `${form.street}, ${form.city}, ${form.region}, ${form.country}`;

      if (form.payment_method === 'paystack' && window.PaystackPop) {
        const handler = window.PaystackPop.setup({
          key: 'pk_live_dc6096b394f4f91bc2b261385951a530f20b94bc',
          email: user.email,
          amount: Math.round(grandTotal * 100),
          currency: 'GHS',
          ref: 'ref_' + Date.now(),
          callback: async (response) => {
            await createOrder({
              user_id: user.id,
              total: grandTotal,
              shipping_address: address,
              phone: form.phone,
              transaction_reference: response.reference,
              items: orderItems,
            });
            clearCart();
            showToast('Order placed successfully!');
            navigate('/orders');
          },
          onClose: () => { setLoading(false); showToast('Payment cancelled.', 'error'); },
        });
        handler.openIframe();
      } else {
        await createOrder({
          user_id: user.id,
          total: grandTotal,
          shipping_address: address,
          phone: form.phone,
          transaction_reference: 'cod_' + Date.now(),
          items: orderItems,
        });
        clearCart();
        showToast('Order placed successfully!');
        navigate('/orders');
      }
    } catch (err) {
      showToast('Failed to place order.', 'error');
      setLoading(false);
    }
  };

  return (
    <>
      <div className="page-header">
        <div className="container">
          <h1 className="page-header__title">Checkout</h1>
          <div className="page-header__breadcrumb">
            <Link to="/">Home</Link><BsChevronRight style={{ fontSize: '.7rem' }} />
            <Link to="/cart">Cart</Link><BsChevronRight style={{ fontSize: '.7rem' }} /><span>Checkout</span>
          </div>
        </div>
      </div>

      <section className="section">
        <div className="container">
          <div className="checkout-steps">
            <button className={`checkout-step ${step >= 1 ? 'checkout-step--active' : ''}`} onClick={() => setStep(1)}>1. Shipping</button>
            <div className="checkout-step-line" />
            <button className={`checkout-step ${step >= 2 ? 'checkout-step--active' : ''}`} onClick={() => step >= 2 && setStep(2)}>2. Payment</button>
          </div>

          <form className="checkout-layout" onSubmit={handleSubmit}>
            <div className="checkout-form">
              {step === 1 && (
                <div className="checkout-section">
                  <h3 className="checkout-section__title">Shipping Address</h3>
                  <div className="form-group"><label className="form-label">Street Address</label><input name="street" className="form-input" value={form.street} onChange={handleChange} required placeholder="123 Main Street" /></div>
                  <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '16px' }}>
                    <div className="form-group"><label className="form-label">City</label><input name="city" className="form-input" value={form.city} onChange={handleChange} required placeholder="Accra" /></div>
                    <div className="form-group"><label className="form-label">Region</label><input name="region" className="form-input" value={form.region} onChange={handleChange} required placeholder="Greater Accra" /></div>
                  </div>
                  <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '16px' }}>
                    <div className="form-group"><label className="form-label">Country</label><input name="country" className="form-input" value={form.country} onChange={handleChange} required /></div>
                    <div className="form-group"><label className="form-label">Phone</label><input name="phone" className="form-input" value={form.phone} onChange={handleChange} required placeholder="+233 20 123 4567" /></div>
                  </div>
                  <div className="form-group">
                    <label className="form-label">Shipping Method</label>
                    <select name="shipping_method" className="form-select" value={form.shipping_method} onChange={handleChange}>
                      <option value="standard">Standard Shipping (Free)</option>
                      <option value="express">Express Shipping</option>
                    </select>
                  </div>
                  <button type="button" className="btn btn--primary btn--lg" onClick={() => { if (form.street && form.city && form.region && form.phone) setStep(2); }}>Continue to Payment</button>
                </div>
              )}
              {step === 2 && (
                <div className="checkout-section">
                  <h3 className="checkout-section__title">Payment Method</h3>
                  <div className="checkout-payment-options">
                    <label className={`checkout-payment-option ${form.payment_method === 'paystack' ? 'checkout-payment-option--active' : ''}`}>
                      <input type="radio" name="payment_method" value="paystack" checked={form.payment_method === 'paystack'} onChange={handleChange} />
                      <div><strong>Paystack</strong><span>Pay with card, mobile money, or bank</span></div>
                    </label>
                    <label className={`checkout-payment-option ${form.payment_method === 'cod' ? 'checkout-payment-option--active' : ''}`}>
                      <input type="radio" name="payment_method" value="cod" checked={form.payment_method === 'cod'} onChange={handleChange} />
                      <div><strong>Cash on Delivery</strong><span>Pay when you receive your order</span></div>
                    </label>
                  </div>
                  <div style={{ display: 'flex', gap: '12px', marginTop: '24px' }}>
                    <button type="button" className="btn btn--outline btn--lg" onClick={() => setStep(1)}>Back</button>
                    <button type="submit" className="btn btn--accent btn--lg" style={{ flex: 1 }} disabled={loading} id="place-order-btn">
                      {loading ? 'Processing...' : `Place Order • ${formatPrice(grandTotal)}`}
                    </button>
                  </div>
                </div>
              )}
            </div>
            <div className="checkout-summary">
              <h3 style={{ fontWeight: 700, marginBottom: '20px' }}>Order Summary</h3>
              {items.map(({ product, quantity }) => (
                <div key={product.id} className="checkout-summary-item">
                  <span style={{ flex: 1 }}>{product.name} × {quantity}</span>
                  <span style={{ fontWeight: 600 }}>{formatPrice(getDiscountedPrice(product) * quantity)}</span>
                </div>
              ))}
              <div className="cart-summary__divider" />
              <div className="cart-summary__row"><span>Subtotal</span><span>{formatPrice(total)}</span></div>
              <div className="cart-summary__row"><span>Tax (7.5%)</span><span>{formatPrice(tax)}</span></div>
              <div className="cart-summary__divider" />
              <div className="cart-summary__row cart-summary__row--total"><span>Total</span><span>{formatPrice(grandTotal)}</span></div>
            </div>
          </form>
        </div>
      </section>
    </>
  );
}
