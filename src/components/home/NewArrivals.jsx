import { useState } from 'react';
import ProductCard from '../products/ProductCard';
import ProductCardSkeleton from '../products/ProductCardSkeleton';

export default function NewArrivals({ newArrivals, loading }) {
  const slugs = Object.keys(newArrivals || {});
  const [active, setActive] = useState(slugs[0] || '');

  // Set first tab as active when arrivals finish loading
  if (slugs.length && !active) {
    setActive(slugs[0]);
  }

  if (!slugs.length && !loading) return null;

  return (
    <section className="section" style={{ background: '#fff' }} id="new-arrivals-section">
      <div className="container">
        <div className="section-heading">
          <div className="section-heading__label">Hurry up to buy</div>
          <h2 className="section-heading__title">New Arrivals</h2>
          <p className="section-heading__subtitle">Discover the latest products from our top categories</p>
        </div>

        <div className="tabs">
          {loading ? (
            Array.from({ length: 3 }).map((_, idx) => (
              <button key={idx} className="tabs__btn skeleton" style={{ width: '100px', height: '36px', borderRadius: '20px', border: 'none' }} disabled />
            ))
          ) : (
            slugs.map(slug => (
              <button
                key={slug}
                className={`tabs__btn ${active === slug ? 'tabs__btn--active' : ''}`}
                onClick={() => setActive(slug)}
              >
                {newArrivals[slug].name.toUpperCase()}
              </button>
            ))
          )}
        </div>

        <div className="grid grid--products">
          {loading ? (
            Array.from({ length: 4 }).map((_, idx) => (
              <ProductCardSkeleton key={idx} />
            ))
          ) : (
            (newArrivals[active]?.products || []).map(p => (
              <ProductCard key={p.id} product={p} category={newArrivals[active].name} />
            ))
          )}
        </div>
      </div>
    </section>
  );
}
