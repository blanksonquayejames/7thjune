import { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { BsChevronRight, BsCartPlus, BsBagCheck, BsStar, BsStarFill } from 'react-icons/bs';
import { getProductBySlug, submitReview } from '../services/api';
import { useCart } from '../context/CartContext';
import { useAuth } from '../context/AuthContext';
import ProductCard from '../components/products/ProductCard';
import { formatPrice, hasActiveDiscount, getDiscountedPrice, getProductBgColor } from '../utils/helpers';
import './ProductDetailPage.css';

export default function ProductDetailPage() {
  const { slug } = useParams();
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [rating, setRating] = useState(5);
  const [comment, setComment] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const { addItem, isInCart } = useCart();
  const { user } = useAuth();

  const loadProduct = () => {
    setLoading(true);
    getProductBySlug(slug).then(d => { setData(d); document.title = d ? `${d.name} - 7th June Computers` : 'Product Not Found'; }).finally(() => setLoading(false));
  };

  useEffect(() => { loadProduct(); }, [slug]);

  if (loading) return <div className="loading-screen"><div className="spinner" /></div>;
  if (!data) return <div className="empty-state"><div className="empty-state__icon">😕</div><h3 className="empty-state__title">Product not found</h3><Link to="/products" className="btn btn--primary">Browse Products</Link></div>;

  const discounted = hasActiveDiscount(data);
  const bg = getProductBgColor(data.id);
  const avgRating = data.reviews.length ? (data.reviews.reduce((s, r) => s + r.rating, 0) / data.reviews.length).toFixed(1) : 0;
  const inCart = isInCart(data.id);

  const handleReview = async (e) => {
    e.preventDefault();
    if (!user) return;
    setSubmitting(true);
    const review = await submitReview(data.id, user.id, rating, comment);
    setData(prev => ({ ...prev, reviews: [...prev.reviews, review] }));
    setComment('');
    setRating(5);
    setSubmitting(false);
  };

  return (
    <>
      <div className="page-header">
        <div className="container">
          <div className="page-header__breadcrumb">
            <Link to="/">Home</Link><BsChevronRight style={{ fontSize: '.7rem' }} />
            <Link to="/products">Products</Link><BsChevronRight style={{ fontSize: '.7rem' }} />
            <span>{data.name}</span>
          </div>
        </div>
      </div>

      <section className="section">
        <div className="container">
          <div className="pdp-grid">
            <div className="pdp-image" style={{ backgroundColor: bg }}>
              {data.image ? <img src={data.image} alt={data.name} /> : <div style={{ fontSize: '6rem' }}>📦</div>}
            </div>
            <div className="pdp-info">
              <span className="pdp-category">{data.category?.name}</span>
              <h1 className="pdp-title">{data.name}</h1>
              <div className="pdp-rating">
                {[1, 2, 3, 4, 5].map(i => i <= Math.round(avgRating) ? <BsStarFill key={i} className="star-rating__star" /> : <BsStar key={i} className="star-rating__star star-rating__star--empty" />)}
                <span style={{ marginLeft: '8px', color: 'var(--gray)', fontSize: '.9rem' }}>({data.reviews.length} reviews)</span>
              </div>
              <div className="pdp-price-row">
                <span className="pdp-price">{formatPrice(getDiscountedPrice(data))}</span>
                {discounted && <span className="pdp-price-original">{formatPrice(data.price)}</span>}
                {discounted && <span className="badge badge--discount">Save {data.discount_percentage}%</span>}
              </div>
              <p className="pdp-desc">{data.description}</p>
              <div className="pdp-meta">
                <span>Stock: <strong style={{ color: data.stock > 0 ? 'var(--success)' : 'var(--danger)' }}>{data.stock > 0 ? `${data.stock} available` : 'Out of stock'}</strong></span>
              </div>
              {data.stock > 0 && (
                <div className="pdp-actions">
                  <button className="btn btn--primary btn--lg" onClick={() => addItem(data)} id="add-to-cart-btn">
                    {inCart ? <><BsBagCheck /> In Cart</> : <><BsCartPlus /> Add to Cart</>}
                  </button>
                </div>
              )}
            </div>
          </div>

          {/* Reviews */}
          <div className="pdp-reviews" id="reviews-section">
            <h3 className="pdp-reviews__title">Reviews ({data.reviews.length})</h3>
            {user && (
              <form className="pdp-review-form" onSubmit={handleReview}>
                <div style={{ display: 'flex', gap: '4px', marginBottom: '12px' }}>
                  {[1, 2, 3, 4, 5].map(i => (
                    <button type="button" key={i} onClick={() => setRating(i)} style={{ background: 'none', border: 'none', cursor: 'pointer', fontSize: '1.3rem', color: i <= rating ? '#facc15' : 'var(--gray-lighter)' }}>★</button>
                  ))}
                </div>
                <textarea className="form-input" rows={3} placeholder="Write your review..." value={comment} onChange={e => setComment(e.target.value)} style={{ marginBottom: '12px', resize: 'vertical' }} />
                <button type="submit" className="btn btn--primary btn--sm" disabled={submitting}>{submitting ? 'Submitting...' : 'Submit Review'}</button>
              </form>
            )}
            {data.reviews.length === 0 ? (
              <p style={{ color: 'var(--gray)', padding: '24px 0' }}>No reviews yet. Be the first!</p>
            ) : (
              <div className="pdp-reviews__list">
                {data.reviews.map(r => (
                  <div key={r.id} className="pdp-review-item">
                    <div className="pdp-review-item__header">
                      <strong>{r.user?.name || 'Anonymous'}</strong>
                      <div className="star-rating">
                        {[1, 2, 3, 4, 5].map(i => i <= r.rating ? <BsStarFill key={i} className="star-rating__star" /> : <BsStar key={i} className="star-rating__star star-rating__star--empty" />)}
                      </div>
                    </div>
                    {r.comment && <p className="pdp-review-item__text">{r.comment}</p>}
                  </div>
                ))}
              </div>
            )}
          </div>

          {/* Related Products */}
          {data.relatedProducts.length > 0 && (
            <div style={{ marginTop: '64px' }}>
              <h3 style={{ fontWeight: 700, fontSize: '1.3rem', marginBottom: '24px' }}>Related Products</h3>
              <div className="grid grid--4">
                {data.relatedProducts.map(p => <ProductCard key={p.id} product={p} />)}
              </div>
            </div>
          )}
        </div>
      </section>
    </>
  );
}
