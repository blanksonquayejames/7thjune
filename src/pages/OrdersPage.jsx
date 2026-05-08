import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { BsChevronRight, BsBoxSeam } from 'react-icons/bs';
import { useAuth } from '../context/AuthContext';
import { getUserOrders } from '../services/api';
import { formatPrice, getStatusBadgeClass } from '../utils/helpers';

export default function OrdersPage() {
  const { user } = useAuth();
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    document.title = 'My Orders - 7th June Computers';
    if (user) getUserOrders(user.id).then(setOrders).finally(() => setLoading(false));
  }, [user]);

  if (loading) return <div className="loading-screen"><div className="spinner" /></div>;

  return (
    <>
      <div className="page-header"><div className="container"><h1 className="page-header__title">My Orders</h1><div className="page-header__breadcrumb"><Link to="/">Home</Link><BsChevronRight style={{ fontSize: '.7rem' }} /><span>Orders</span></div></div></div>
      <section className="section">
        <div className="container">
          {orders.length === 0 ? (
            <div className="empty-state"><div className="empty-state__icon"><BsBoxSeam /></div><h3 className="empty-state__title">No orders yet</h3><p className="empty-state__text">Start shopping to see your orders here</p><Link to="/products" className="btn btn--primary">Browse Products</Link></div>
          ) : (
            <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
              {orders.map(order => (
                <Link to={`/orders/${order.id}`} key={order.id} className="card" style={{ padding: '24px', display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '16px' }} id={`order-${order.id}`}>
                  <div>
                    <strong style={{ fontSize: '1rem' }}>Order #{order.id}</strong>
                    <span style={{ display: 'block', fontSize: '.85rem', color: 'var(--gray)', marginTop: '4px' }}>{new Date(order.created_at).toLocaleDateString()}</span>
                  </div>
                  <span className={`badge ${getStatusBadgeClass(order.status)}`}>{order.status}</span>
                  <span style={{ fontWeight: 700, fontSize: '1.1rem', color: 'var(--primary)' }}>{formatPrice(order.total)}</span>
                  <span style={{ color: 'var(--gray)', fontSize: '.85rem' }}>{order.items.length} item{order.items.length > 1 ? 's' : ''}</span>
                </Link>
              ))}
            </div>
          )}
        </div>
      </section>
    </>
  );
}
