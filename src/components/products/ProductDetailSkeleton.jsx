import '../common/Skeleton.css';

export default function ProductDetailSkeleton() {
  return (
    <div className="container" aria-hidden="true" style={{ padding: '48px 24px' }}>
      <div className="pdp-skeleton-grid">
        {/* Left side: Image */}
        <div className="pdp-skeleton-image skeleton" />
        
        {/* Right side: Info */}
        <div className="pdp-skeleton-info">
          <div className="pdp-skeleton-category skeleton" />
          <div className="pdp-skeleton-title skeleton" />
          <div className="pdp-skeleton-rating skeleton" />
          <div className="pdp-skeleton-price skeleton" />
          
          <div className="pdp-skeleton-desc">
            <div className="pdp-skeleton-desc-line skeleton" />
            <div className="pdp-skeleton-desc-line skeleton" />
            <div className="pdp-skeleton-desc-line skeleton" />
            <div className="pdp-skeleton-desc-line pdp-skeleton-desc-line--short skeleton" />
          </div>
          
          <div className="pdp-skeleton-meta skeleton" />
          <div className="pdp-skeleton-button skeleton" />
        </div>
      </div>
    </div>
  );
}
