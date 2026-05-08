import { useNavigate } from 'react-router-dom';
import { BsCartPlus } from 'react-icons/bs';
import { useCart } from '../../context/CartContext';
import { formatPrice, hasActiveDiscount, getDiscountedPrice, getProductBgColor } from '../../utils/helpers';

export default function ProductCard({ product, category }) {
  const navigate = useNavigate();
  const { addItem } = useCart();
  const bg = getProductBgColor(product.id);
  const discounted = hasActiveDiscount(product);
  const catName = category || product.category?.name || '';

  const handleClick = () => navigate(`/products/${product.slug}`);
  const handleAddCart = (e) => {
    e.stopPropagation();
    addItem(product);
  };

  return (
    <div className="card product-card" onClick={handleClick} id={`product-card-${product.id}`}>
      <div className="product-card__image-wrap" style={{ backgroundColor: bg }}>
        {product.image ? (
          <img src={product.image} alt={product.name} className="product-card__image" />
        ) : (
          <div style={{ fontSize: '3rem', color: 'var(--gray-lighter)' }}>📦</div>
        )}
        <div className="product-card__badges">
          {product.stock === 0 && <span className="badge badge--sold-out">Sold Out</span>}
          {product.stock > 0 && discounted && <span className="badge badge--discount">-{product.discount_percentage}%</span>}
          {product.stock > 0 && !discounted && product.is_hot && <span className="badge badge--hot">Hot</span>}
        </div>
      </div>
      <div className="product-card__body">
        <h6 className="product-card__name">{product.name}</h6>
        <span className="product-card__category">{catName}</span>
        <div>
          <span className="product-card__price">{formatPrice(getDiscountedPrice(product))}</span>
          {discounted && <span className="product-card__price--original">{formatPrice(product.price)}</span>}
        </div>
      </div>
      {product.stock > 0 && (
        <button className="product-card__cart-btn" onClick={handleAddCart} title="Add to Cart">
          <BsCartPlus />
        </button>
      )}
    </div>
  );
}
