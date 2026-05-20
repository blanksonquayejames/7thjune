import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { BsCartPlus, BsCartCheckFill } from 'react-icons/bs';
import { useCart } from '../../context/CartContext';
import { formatPrice, hasActiveDiscount, getDiscountedPrice, getProductBgColor } from '../../utils/helpers';

export default function ProductCard({ product, category }) {
  const router = useRouter();
  const { addItem, isInCart } = useCart();
  const [isAdding, setIsAdding] = useState(false);
  const bg = getProductBgColor(product.id);
  const discounted = hasActiveDiscount(product);
  const catName = category || product.category?.name || '';
  const alreadyInCart = isInCart(product.id);

  const handleClick = () => router.push(`/products/${product.slug}`);
  const handleCartAction = (e) => {
    e.stopPropagation();
    if (alreadyInCart) {
      router.push('/cart');
    } else {
      setIsAdding(true);
      setTimeout(() => {
        addItem(product);
        setIsAdding(false);
      }, 600);
    }
  };

  const handleKeyDown = (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      handleClick();
    }
  };

  return (
    <div 
      className="card product-card" 
      onClick={handleClick} 
      onKeyDown={handleKeyDown}
      id={`product-card-${product.id}`}
      tabIndex="0"
      role="button"
      aria-label={`View details for ${product.name}`}
    >
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
        <button
          className={`product-card__cart-btn ${alreadyInCart ? 'product-card__cart-btn--in-cart' : ''}`}
          onClick={handleCartAction}
          disabled={isAdding}
          title={alreadyInCart ? 'View in Cart' : 'Add to Cart'}
          aria-label={alreadyInCart ? `View ${product.name} in cart` : `Add ${product.name} to cart`}
        >
          {isAdding ? (
            <span className="spinner-sm" style={{ width: '16px', height: '16px', borderLeftColor: 'transparent', display: 'inline-block' }} />
          ) : alreadyInCart ? (
            <BsCartCheckFill />
          ) : (
            <BsCartPlus />
          )}
        </button>
      )}
    </div>
  );
}
