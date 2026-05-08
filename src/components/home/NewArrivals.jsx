import { useState } from 'react';
import ProductCard from '../products/ProductCard';

export default function NewArrivals({ newArrivals }) {
  const slugs = Object.keys(newArrivals);
  const [active, setActive] = useState(slugs[0] || '');

  if (!slugs.length) return null;

  return (
    <section className="section" style={{ background: '#fff' }} id="new-arrivals-section">
      <div className="container">
        <div className="section-heading">
          <div className="section-heading__label">Hurry up to buy</div>
          <h2 className="section-heading__title">New Arrivals</h2>
          <p className="section-heading__subtitle">Discover the latest products from our top categories</p>
        </div>

        <div className="tabs">
          {slugs.map(slug => (
            <button
              key={slug}
              className={`tabs__btn ${active === slug ? 'tabs__btn--active' : ''}`}
              onClick={() => setActive(slug)}
            >
              {newArrivals[slug].name.toUpperCase()}
            </button>
          ))}
        </div>

        <div className="grid grid--products">
          {(newArrivals[active]?.products || []).map(p => (
            <ProductCard key={p.id} product={p} category={newArrivals[active].name} />
          ))}
        </div>
      </div>
    </section>
  );
}
