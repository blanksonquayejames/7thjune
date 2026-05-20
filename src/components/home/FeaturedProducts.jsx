import Link from 'next/link';
import ProductCard from '../products/ProductCard';
import ProductCardSkeleton from '../products/ProductCardSkeleton';

export default function FeaturedProducts({ products, loading }) {
  return (
    <section className="section" style={{ background: '#fff' }} id="featured-products-section">
      <div className="container">
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-end', marginBottom: '24px' }}>
          <h4 style={{ fontWeight: 700, fontSize: '1.4rem', color: 'var(--dark)' }}>Featured Products</h4>
          <Link href="/products" style={{ fontWeight: 700, fontSize: '.85rem', color: 'var(--primary)' }}>See All</Link>
        </div>
        <div className="grid grid--4">
          {loading ? (
            Array.from({ length: 4 }).map((_, idx) => (
              <ProductCardSkeleton key={idx} />
            ))
          ) : (
            (products || []).slice(0, 4).map(p => (
              <ProductCard key={p.id} product={p} />
            ))
          )}
        </div>
      </div>
    </section>
  );
}
