import { useState, useEffect } from 'react';
import Link from 'next/link';
import './HeroCarousel.css';

const slides = [
  { src: '/images/hero/slide1.png', alt: 'Tech Devices' },
  { src: '/images/hero/slide2.png', alt: 'Fashion' },
  { src: '/images/hero/slide3.png', alt: 'Home & Garden' },
];

export default function HeroCarousel() {
  const [current, setCurrent] = useState(0);

  useEffect(() => {
    const timer = setInterval(() => setCurrent(c => (c + 1) % slides.length), 4000);
    return () => clearInterval(timer);
  }, []);

  return (
    <section className="hero" id="hero-section">
      <div className="hero__bg">
        {slides.map((s, i) => (
          <img key={i} src={s.src} alt={s.alt} className={`hero__bg-img ${i === current ? 'hero__bg-img--active' : ''}`} />
        ))}
        <div className="hero__overlay" />
      </div>
      <div className="container hero__content">
        <h1 className="hero__title">ALL THE TECH YOU<br />NEED, ALL IN ONE PLACE</h1>
        <p className="hero__text">
          Discover the latest electronics, powerful computers, cutting-edge mobiles, security gadgets,
          accessories, and more — all from trusted sellers and at unbeatable prices.
        </p>
        <Link href="/products" className="btn btn--accent btn--lg btn--pill hero__cta">
          Shop Now
          <span className="hero__cta-arrow">↗</span>
        </Link>
      </div>
      <div className="hero__dots">
        {slides.map((_, i) => (
          <button key={i} className={`hero__dot ${i === current ? 'hero__dot--active' : ''}`} onClick={() => setCurrent(i)} aria-label={`Slide ${i + 1}`} />
        ))}
      </div>
    </section>
  );
}
