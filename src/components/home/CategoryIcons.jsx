import { Link } from 'react-router-dom';
import { BsCpu, BsHdd, BsWifi, BsTablet, BsMouse, BsGrid } from 'react-icons/bs';
import './CategoryIcons.css';

const iconMap = {
  'computers': BsCpu,
  'storage-components': BsHdd,
  'networking': BsWifi,
  'tablets': BsTablet,
  'peripherals': BsMouse,
};

export default function CategoryIcons({ categories }) {
  return (
    <section className="section category-icons" id="category-icons-section">
      <div className="container">
        <div className="category-icons__grid">
          {categories.map(cat => {
            const Icon = iconMap[cat.slug] || BsGrid;
            return (
              <Link to={`/products?category=${cat.slug}`} key={cat.id} className="category-icon">
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
          })}
        </div>
      </div>
    </section>
  );
}
