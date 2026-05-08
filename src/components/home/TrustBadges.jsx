import { BsTruck, BsShieldCheck, BsArrowRepeat, BsHeadset } from 'react-icons/bs';
import './TrustBadges.css';

const badges = [
  { icon: BsTruck, title: 'Free Shipping', desc: 'On orders over ₵5000', color: '#2563eb', bg: 'rgba(37,99,235,0.06)' },
  { icon: BsShieldCheck, title: 'Secure Payment', desc: '100% protected', color: '#10b981', bg: 'rgba(16,185,129,0.06)' },
  { icon: BsArrowRepeat, title: 'Easy Returns', desc: '30-day returns', color: '#f59e0b', bg: 'rgba(245,158,11,0.06)' },
  { icon: BsHeadset, title: '24/7 Support', desc: 'Always here for you', color: '#6c5ce7', bg: 'rgba(108,92,231,0.06)' },
];

export default function TrustBadges() {
  return (
    <section className="section trust-badges" id="trust-badges-section">
      <div className="container">
        <div className="trust-badges__grid">
          {badges.map((b, i) => (
            <div key={i} className="trust-badge">
              <div className="trust-badge__icon" style={{ background: b.bg, color: b.color }}>
                <b.icon />
              </div>
              <h6 className="trust-badge__title">{b.title}</h6>
              <span className="trust-badge__desc">{b.desc}</span>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}
