import { useState, useEffect } from 'react';
import HeroCarousel from '../components/home/HeroCarousel';
import CategoryIcons from '../components/home/CategoryIcons';
import NewArrivals from '../components/home/NewArrivals';
import FeaturedProducts from '../components/home/FeaturedProducts';
import TrustBadges from '../components/home/TrustBadges';
import { getCategories, getFeaturedProducts, getNewArrivalsByCategory } from '../services/api';

export default function HomePage() {
  const [categories, setCategories] = useState([]);
  const [featured, setFeatured] = useState([]);
  const [newArrivals, setNewArrivals] = useState({});
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    document.title = '7th June Computers - All the tech you need';
    Promise.all([getCategories(), getFeaturedProducts(), getNewArrivalsByCategory()])
      .then(([cats, feat, arrivals]) => {
        setCategories(cats);
        setFeatured(feat);
        setNewArrivals(arrivals);
      })
      .finally(() => setLoading(false));
  }, []);

  return (
    <>
      <HeroCarousel />
      <CategoryIcons categories={categories} loading={loading} />
      <NewArrivals newArrivals={newArrivals} loading={loading} />
      <FeaturedProducts products={featured} loading={loading} />
      <TrustBadges />
    </>
  );
}
