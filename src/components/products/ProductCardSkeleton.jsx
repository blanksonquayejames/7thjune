import '../common/Skeleton.css';

export default function ProductCardSkeleton() {
  return (
    <div className="product-card-skeleton" aria-hidden="true">
      <div className="product-card-skeleton__image skeleton" />
      <div className="product-card-skeleton__body">
        <div className="product-card-skeleton__category skeleton" />
        <div className="product-card-skeleton__title skeleton" />
        <div className="product-card-skeleton__price skeleton" />
      </div>
      <div className="product-card-skeleton__button skeleton" />
    </div>
  );
}

export function ProductSkeletonGrid({ count = 8 }) {
  return (
    <div className="grid grid--products">
      {Array.from({ length: count }).map((_, idx) => (
        <ProductCardSkeleton key={idx} />
      ))}
    </div>
  );
}
