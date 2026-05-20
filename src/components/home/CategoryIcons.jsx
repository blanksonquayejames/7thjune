import Link from 'next/link';
import { BsCpu, BsHdd, BsWifi, BsTablet, BsMouse, BsGrid } from 'react-icons/bs';
import './CategoryIcons.css';

const iconMap = {
  'computers': BsCpu,
  'storage-components': BsHdd,
  'networking': BsWifi,
  'tablets': BsTablet,
  'peripherals': BsMouse,
};

export default function CategoryIcons({ categories, loading }) {
  return (
    <section className="section category-icons" id="category-icons-section">
      <div className="container">
        <div className="category-icons__grid">
          {loading ? (
            Array.from({ length: 5 }).map((_, idx) => (
              <div key={idx} className="category-icon" style={{ pointerEvents: 'none' }}>
                <div className="category-icon__circle skeleton" style={{ border: 'none' }} />
                <div className="skeleton" style={{ height: '14px', width: '70px', borderRadius: '4px', margin: '12px auto 4px auto' }} />
                <div className="skeleton" style={{ height: '10px', width: '50px', borderRadius: '3px', margin: '0 auto' }} />
              </div>
            ))
          ) : (
            categories.map(cat => {
              const Icon = iconMap[cat.slug] || BsGrid;
              return (
                <Link href={`/products?category=${cat.slug}`} key={cat.id} className="category-icon">
                  <div className="category-icon__circle">
                    {cat.image ? (
                      <img src={cat.image} alt={cat.name} className="category-icon__img" />
                    ) : (
                      <Icon />
                    )}
                  </div>
                  <h6 className="category-icon__name">{cat.name}</h6>
                  <span className="category-icon__count">{cat.products_count} products</span>
                </Link>
              );
            })
          )}
        </div>
      </div>
    </section>
  );
}
