import { useState, useRef, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { BsChevronRight } from 'react-icons/bs';
import { useCart } from '../context/CartContext';
import { useAuth } from '../context/AuthContext';
import { createOrder } from '../services/api';
import { formatPrice, getDiscountedPrice } from '../utils/helpers';
import './CheckoutPage.css';

export default function CheckoutPage() {
  const { items, total, clearCart, showToast } = useCart();
  const { user } = useAuth();
  const router = useRouter();
  
  const [form, setForm] = useState({
    street: '',
    city: '',
    region: '',
    country: 'Ghana',
    phone: '',
    shipping_method: 'standard',
    payment_method: 'paystack'
  });
  
  const [loading, setLoading] = useState(false);
  const [step, setStep] = useState(1);
  
  // Validation States
  const [touched, setTouched] = useState({});
  const [errors, setErrors] = useState({});
  const [shakeForm, setShakeForm] = useState(false);

  // Promo Code States
  const [promoCode, setPromoCode] = useState('');
  const [promoLoading, setPromoLoading] = useState(false);
  const [promoApplied, setPromoApplied] = useState(false);
  const [promoError, setPromoError] = useState('');
  const [discountAmount, setDiscountAmount] = useState(0);

  useEffect(() => {
    document.title = 'Checkout - 7th June Computers';
  }, []);

  if (items.length === 0) {
    return (
      <div className="empty-state">
        <div className="empty-state__icon">🛒</div>
        <h3 className="empty-state__title">Cart is empty</h3>
        <Link href="/products" className="btn btn--primary">Browse Products</Link>
      </div>
    );
  }

  // Active phone number masking (+233 format)
  const formatPhoneNumber = (value) => {
    const cleaned = value.replace(/\D/g, '');
    if (cleaned.startsWith('233')) {
      const part1 = cleaned.slice(3, 5);
      const part2 = cleaned.slice(5, 8);
      const part3 = cleaned.slice(8, 12);
      
      let formatted = '+233';
      if (part1) formatted += ` ${part1}`;
      if (part2) formatted += ` ${part2}`;
      if (part3) formatted += ` ${part3}`;
      return formatted;
    } else {
      const part1 = cleaned.slice(0, 3);
      const part2 = cleaned.slice(3, 6);
      const part3 = cleaned.slice(6, 10);
      
      let formatted = '';
      if (part1) formatted += part1;
      if (part2) formatted += ` ${part2}`;
      if (part3) formatted += ` ${part3}`;
      return formatted;
    }
  };

  const validateField = (name, val) => {
    let err = '';
    if (!val) {
      err = 'This field is required';
    } else if (name === 'street' && val.trim().length < 5) {
      err = 'Please enter a valid street address (min 5 characters)';
    } else if (name === 'city' && val.trim().length < 2) {
      err = 'Please enter a valid city name';
    } else if (name === 'region' && val.trim().length < 2) {
      err = 'Please enter a valid region';
    } else if (name === 'country' && val.trim().length < 2) {
      err = 'Please enter a valid country';
    } else if (name === 'phone') {
      const digits = val.replace(/\D/g, '');
      if (digits.length < 9) {
        err = 'Please enter a valid phone number (min 9 digits)';
      }
    }
    setErrors(prev => ({ ...prev, [name]: err }));
    return err;
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    let val = value;
    if (name === 'phone') {
      val = formatPhoneNumber(value);
    }
    setForm(prev => ({ ...prev, [name]: val }));
    if (touched[name]) {
      validateField(name, val);
    }
  };

  const handleBlur = (e) => {
    const { name, value } = e.target;
    setTouched(prev => ({ ...prev, [name]: true }));
    validateField(name, value);
  };

  const getInputClass = (name) => {
    if (!touched[name]) return 'form-input';
    return errors[name] ? 'form-input form-input--invalid' : 'form-input form-input--valid';
  };

  // Continue button step transition validation
  const handleContinueToPayment = () => {
    const fields = ['street', 'city', 'region', 'country', 'phone'];
    let hasErrors = false;
    const newTouched = {};
    const newErrors = {};

    fields.forEach(f => {
      newTouched[f] = true;
      const val = form[f] || '';
      const err = validateField(f, val);
      if (err) {
        newErrors[f] = err;
        hasErrors = true;
      }
    });

    setTouched(prev => ({ ...prev, ...newTouched }));
    setErrors(prev => ({ ...prev, ...newErrors }));

    if (hasErrors) {
      setShakeForm(true);
      setTimeout(() => setShakeForm(false), 450);
      showToast('Please correct validation errors on shipping address.', 'error');
    } else {
      setStep(2);
    }
  };

  // Apply promo discount
  const handleApplyPromo = (e) => {
    e.preventDefault();
    if (!promoCode.trim()) return;
    setPromoLoading(true);
    setPromoError('');
    
    // Simulate promo code lookup
    setTimeout(() => {
      const upper = promoCode.toUpperCase().trim();
      if (upper === 'SAVE10') {
        setPromoApplied(true);
        const discount = total * 0.10; // 10% Off
        setDiscountAmount(discount);
        setPromoLoading(false);
        showToast('Promo code applied successfully!', 'success');
      } else {
        setPromoError('Invalid promo code. Try "SAVE10".');
        setPromoLoading(false);
        showToast('Invalid promo code.', 'error');
      }
    }, 1000);
  };

  const discount = promoApplied ? discountAmount : 0;
  const taxableAmount = Math.max(0, total - discount);
  const tax = taxableAmount * 0.075;
  const grandTotal = taxableAmount + tax;

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Safety check fields
    const fields = ['street', 'city', 'region', 'country', 'phone'];
    let hasErrors = false;
    const newErrors = {};
    fields.forEach(f => {
      const val = form[f] || '';
      const err = validateField(f, val);
      if (err) {
        newErrors[f] = err;
        hasErrors = true;
      }
    });

    if (hasErrors) {
      setTouched(prev => ({ ...prev, street: true, city: true, region: true, country: true, phone: true }));
      setErrors(prev => ({ ...prev, ...newErrors }));
      setStep(1); // Return to Shipping Form
      setShakeForm(true);
      setTimeout(() => setShakeForm(false), 450);
      showToast('Please enter valid shipping details.', 'error');
      return;
    }

    setLoading(true);
    try {
      const orderItems = items.map(i => ({
        product_id: i.product.id,
        quantity: i.quantity,
        price: getDiscountedPrice(i.product)
      }));
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
            showToast('Order processed successfully!');
            router.push('/orders');
          },
          onClose: () => {
            setLoading(false);
            showToast('Transaction cancelled by user.', 'error');
          },
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
        showToast('Order processed successfully!');
        router.push('/orders');
      }
    } catch (err) {
      showToast('Unable to process order. Please verify your details.', 'error');
      setLoading(false);
    }
  };

  return (
    <>
      <div className="page-header">
        <div className="container">
          <h1 className="page-header__title">Checkout</h1>
          <div className="page-header__breadcrumb">
            <Link href="/">Home</Link><BsChevronRight style={{ fontSize: '.7rem' }} />
            <Link href="/cart">Cart</Link><BsChevronRight style={{ fontSize: '.7rem' }} />
            <span>Checkout</span>
          </div>
        </div>
      </div>

      <section className="section">
        <div className="container">
          <div className="checkout-steps">
            <button 
              type="button" 
              className={`checkout-step ${step >= 1 ? 'checkout-step--active' : ''}`} 
              onClick={() => setStep(1)}
              aria-label="Go to shipping address step"
            >
              1. Shipping
            </button>
            <div className="checkout-step-line" />
            <button 
              type="button" 
              className={`checkout-step ${step >= 2 ? 'checkout-step--active' : ''}`} 
              onClick={() => { if (form.street && form.city && form.phone) setStep(2); }}
              aria-label="Go to payment method step"
            >
              2. Payment
            </button>
          </div>

          <form className="checkout-layout" onSubmit={handleSubmit} noValidate>
            <div className={`checkout-form ${shakeForm ? 'form-shake' : ''}`}>
              {step === 1 && (
                <div className="checkout-section">
                  <h3 className="checkout-section__title">Shipping Address</h3>
                  
                  <div className="form-group">
                    <label className="form-label">Street Address</label>
                    <input 
                      name="street" 
                      className={getInputClass('street')} 
                      value={form.street} 
                      onChange={handleChange} 
                      onBlur={handleBlur}
                      required 
                      placeholder="123 Main Street" 
                      aria-invalid={!!errors.street}
                      aria-describedby={errors.street ? "street-error" : undefined}
                    />
                    {touched.street && errors.street && (
                      <span className="form-error" id="street-error">{errors.street}</span>
                    )}
                  </div>

                  <div className="checkout-row-2">
                    <div className="form-group">
                      <label className="form-label">City</label>
                      <input 
                        name="city" 
                        className={getInputClass('city')} 
                        value={form.city} 
                        onChange={handleChange} 
                        onBlur={handleBlur}
                        required 
                        placeholder="Accra" 
                        aria-invalid={!!errors.city}
                        aria-describedby={errors.city ? "city-error" : undefined}
                      />
                      {touched.city && errors.city && (
                        <span className="form-error" id="city-error">{errors.city}</span>
                      )}
                    </div>
                    
                    <div className="form-group">
                      <label className="form-label">Region</label>
                      <input 
                        name="region" 
                        className={getInputClass('region')} 
                        value={form.region} 
                        onChange={handleChange} 
                        onBlur={handleBlur}
                        required 
                        placeholder="Greater Accra" 
                        aria-invalid={!!errors.region}
                        aria-describedby={errors.region ? "region-error" : undefined}
                      />
                      {touched.region && errors.region && (
                        <span className="form-error" id="region-error">{errors.region}</span>
                      )}
                    </div>
                  </div>

                  <div className="checkout-row-2">
                    <div className="form-group">
                      <label className="form-label">Country</label>
                      <input 
                        name="country" 
                        className={getInputClass('country')} 
                        value={form.country} 
                        onChange={handleChange} 
                        onBlur={handleBlur}
                        required 
                        aria-invalid={!!errors.country}
                        aria-describedby={errors.country ? "country-error" : undefined}
                      />
                      {touched.country && errors.country && (
                        <span className="form-error" id="country-error">{errors.country}</span>
                      )}
                    </div>
                    
                    <div className="form-group">
                      <label className="form-label">Phone</label>
                      <input 
                        name="phone" 
                        type="tel"
                        className={getInputClass('phone')} 
                        value={form.phone} 
                        onChange={handleChange} 
                        onBlur={handleBlur}
                        required 
                        placeholder="+233 20 123 4567" 
                        aria-invalid={!!errors.phone}
                        aria-describedby={errors.phone ? "phone-error" : undefined}
                      />
                      {touched.phone && errors.phone && (
                        <span className="form-error" id="phone-error">{errors.phone}</span>
                      )}
                    </div>
                  </div>

                  <div className="form-group">
                    <label className="form-label">Shipping Method</label>
                    <select name="shipping_method" className="form-select" value={form.shipping_method} onChange={handleChange}>
                      <option value="standard">Standard Shipping (Free)</option>
                      <option value="express">Express Shipping</option>
                    </select>
                  </div>
                  
                  <button 
                    type="button" 
                    className="btn btn--primary btn--lg" 
                    onClick={handleContinueToPayment}
                  >
                    Continue to Payment
                  </button>
                </div>
              )}
              
              {step === 2 && (
                <div className="checkout-section">
                  <h3 className="checkout-section__title">Payment Method</h3>
                  <div className="checkout-payment-options">
                    <label className={`checkout-payment-option ${form.payment_method === 'paystack' ? 'checkout-payment-option--active' : ''}`}>
                      <input 
                        type="radio" 
                        name="payment_method" 
                        value="paystack" 
                        checked={form.payment_method === 'paystack'} 
                        onChange={handleChange} 
                      />
                      <div>
                        <strong>Paystack</strong>
                        <span>Pay with card, mobile money, or bank transfer</span>
                      </div>
                    </label>
                    <label className={`checkout-payment-option ${form.payment_method === 'cod' ? 'checkout-payment-option--active' : ''}`}>
                      <input 
                        type="radio" 
                        name="payment_method" 
                        value="cod" 
                        checked={form.payment_method === 'cod'} 
                        onChange={handleChange} 
                      />
                      <div>
                        <strong>Cash on Delivery</strong>
                        <span>Pay in cash upon receiving your package</span>
                      </div>
                    </label>
                  </div>
                  
                  <div style={{ display: 'flex', gap: '12px', marginTop: '24px' }}>
                    <button type="button" className="btn btn--outline btn--lg" onClick={() => setStep(1)}>Back</button>
                    <button 
                      type="submit" 
                      className="btn btn--accent btn--lg" 
                      style={{ flex: 1 }} 
                      disabled={loading} 
                      id="place-order-btn"
                    >
                      {loading ? (
                        <>
                          <div className="spinner-sm" style={{ marginRight: '8px' }} />
                          Processing Securely...
                        </>
                      ) : (
                        `Place Order • ${formatPrice(grandTotal)}`
                      )}
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
              
              {/* Promo code widget */}
              <div className="promo-code-widget" style={{ margin: '16px 0', padding: '16px 0', borderTop: '1px dashed var(--gray-lighter)', borderBottom: '1px dashed var(--gray-lighter)' }}>
                <label className="form-label">Promo Discount</label>
                <div style={{ display: 'flex', gap: '8px', marginTop: '4px' }}>
                  <input 
                    type="text" 
                    className="form-input" 
                    placeholder="ENTER CODE (e.g. SAVE10)" 
                    value={promoCode} 
                    onChange={e => setPromoCode(e.target.value)}
                    disabled={promoApplied || promoLoading}
                    aria-label="Promo code input"
                  />
                  <button 
                    type="button" 
                    className={`btn ${promoApplied ? 'btn--success' : 'btn--primary'} btn--sm`}
                    onClick={handleApplyPromo}
                    disabled={promoLoading || promoApplied || !promoCode.trim()}
                  >
                    {promoLoading ? (
                      <div className="spinner-sm" />
                    ) : promoApplied ? (
                      'Applied'
                    ) : (
                      'Apply'
                    )}
                  </button>
                </div>
                {promoError && <span className="form-error" style={{ display: 'block', marginTop: '4px' }}>{promoError}</span>}
                {promoApplied && <span style={{ color: 'var(--success)', fontSize: '.8rem', display: 'block', marginTop: '4px' }}>10% discount successfully applied!</span>}
              </div>

              {promoApplied && (
                <div className="cart-summary__row" style={{ color: 'var(--success)', fontWeight: 600 }}>
                  <span>Discount (10%)</span>
                  <span>-{formatPrice(discountAmount)}</span>
                </div>
              )}
              
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
