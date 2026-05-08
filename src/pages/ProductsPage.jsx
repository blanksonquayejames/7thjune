import { useState, useEffect } from 'react';
import { useSearchParams, Link } from 'react-router-dom';
import ProductCard from '../components/products/ProductCard';
import { getProducts, getCategories } from '../services/api';
import { BsChevronRight } from 'react-icons/bs';

export default function ProductsPage() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);

  const category = searchParams.get('category') || '';
  const search = searchParams.get('search') || '';
  const sort = searchParams.get('sort') || 'latest';

  useEffect(() => {
    document.title = 'Products - 7th June Computers';
    setLoading(true);
    Promise.all([
      getProducts({ category, search, sort }),
      getCategories()
    ]).then(([prods, cats]) => {
      setProducts(prods);
      setCategories(cats);
    }).finally(() => setLoading(false));
  }, [category, search, sort]);

  const updateParam = (key, value) => {
    const params = new URLSearchParams(searchParams);
    if (value) params.set(key, value);
    else params.delete(key);
    setSearchParams(params);
  };

  return (
    <>
      <div className="page-header">
        <div className="container">
          <h1 className="page-header__title">Products</h1>
          <div className="page-header__breadcrumb">
            <Link to="/">Home</Link>
            <BsChevronRight style={{ fontSize: '.7rem' }} />
            <span>Products</span>
            {category && <><BsChevronRight style={{ fontSize: '.7rem' }} /><span style={{ textTransform: 'capitalize' }}>{category.replace('-', ' ')}</span></>}
          </div>
        </div>
      </div>

      <section className="section">
        <div className="container">
          <div style={{ display: 'flex', gap: '16px', flexWrap: 'wrap', marginBottom: '32px', alignItems: 'center' }}>
            <select className="form-select" style={{ maxWidth: '200px' }} value={category} onChange={e => updateParam('category', e.target.value)} id="category-filter">
              <option value="">All Categories</option>
              {categories.map(c => <option key={c.id} value={c.slug}>{c.name}</option>)}
            </select>
            <select className="form-select" style={{ maxWidth: '200px' }} value={sort} onChange={e => updateParam('sort', e.target.value)} id="sort-filter">
              <option value="latest">Latest</option>
              <option value="price_low">Price: Low to High</option>
              <option value="price_high">Price: High to Low</option>
              <option value="name">Name A-Z</option>
            </select>
            {search && (
              <div style={{ marginLeft: 'auto', color: 'var(--gray)', fontSize: '.9rem' }}>
                Showing results for "<strong>{search}</strong>"
                <button className="btn btn--ghost btn--sm" onClick={() => updateParam('search', '')} style={{ marginLeft: '8px' }}>Clear</button>
              </div>
            )}
          </div>

          {loading ? (
            <div className="loading-screen"><div className="spinner" /></div>
          ) : products.length === 0 ? (
            <div className="empty-state">
              <div className="empty-state__icon">🔍</div>
              <h3 className="empty-state__title">No products found</h3>
              <p className="empty-state__text">Try adjusting your filters or search terms</p>
            </div>
          ) : (
            <div className="grid grid--products">
              {products.map(p => <ProductCard key={p.id} product={p} />)}
            </div>
          )}
        </div>
      </section>
    </>
  );
}
