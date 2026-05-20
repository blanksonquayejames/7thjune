import { useState, useEffect } from 'react';
import { useParams } from 'next/navigation';
import Link from 'next/link';
import { BsChevronRight } from 'react-icons/bs';
import { useAuth } from '../context/AuthContext';
import { getOrderById } from '../services/api';
import { formatPrice, getStatusBadgeClass } from '../utils/helpers';

export default function OrderDetailPage() {
  const { id } = useParams();
  const { user } = useAuth();
  const [order, setOrder] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    document.title = `Order #${id} - 7th June Computers`;
    if (user) getOrderById(user.id, id).then(setOrder).finally(() => setLoading(false));
  }, [user, id]);

  if (loading) return <div className="loading-screen"><div className="spinner" /></div>;
  if (!order) return <div className="empty-state"><h3 className="empty-state__title">Order not found</h3><Link href="/orders" className="btn btn--primary">Back to Orders</Link></div>;

  return (
    <>
      <div className="page-header"><div className="container"><h1 className="page-header__title">Order #{order.id}</h1><div className="page-header__breadcrumb"><Link href="/">Home</Link><BsChevronRight style={{ fontSize: '.7rem' }} /><Link href="/orders">Orders</Link><BsChevronRight style={{ fontSize: '.7rem' }} /><span>#{order.id}</span></div></div></div>
      <section className="section">
        <div className="container" style={{ maxWidth: '800px' }}>
          <div className="card" style={{ padding: '32px', marginBottom: '24px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', flexWrap: 'wrap', gap: '16px', marginBottom: '24px' }}>
              <div><strong style={{ fontSize: '1.1rem' }}>Order #{order.id}</strong><span style={{ display: 'block', fontSize: '.85rem', color: 'var(--gray)', marginTop: '4px' }}>{new Date(order.created_at).toLocaleDateString()}</span></div>
              <span className={`badge ${getStatusBadgeClass(order.status)}`} style={{ fontSize: '.8rem', padding: '6px 14px' }}>{order.status}</span>
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '20px', marginBottom: '24px' }}>
              <div><label style={{ fontSize: '.8rem', color: 'var(--gray)', fontWeight: 600 }}>SHIPPING ADDRESS</label><p style={{ fontSize: '.9rem', marginTop: '4px' }}>{order.shipping_address}</p></div>
              <div><label style={{ fontSize: '.8rem', color: 'var(--gray)', fontWeight: 600 }}>PHONE</label><p style={{ fontSize: '.9rem', marginTop: '4px' }}>{order.phone}</p></div>
            </div>
          </div>
          <div className="card" style={{ padding: '32px' }}>
            <h3 style={{ fontWeight: 700, marginBottom: '20px' }}>Items</h3>
            {order.items.map((item, i) => (
              <div key={i} style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', padding: '12px 0', borderBottom: i < order.items.length - 1 ? '1px solid var(--light)' : 'none' }}>
                <div><strong style={{ fontSize: '.95rem' }}>{item.product?.name || `Product #${item.product_id}`}</strong><span style={{ display: 'block', fontSize: '.82rem', color: 'var(--gray)' }}>Qty: {item.quantity} × {formatPrice(item.price)}</span></div>
                <span style={{ fontWeight: 700, color: 'var(--primary)' }}>{formatPrice(item.price * item.quantity)}</span>
              </div>
            ))}
            <div style={{ borderTop: '2px solid var(--dark)', marginTop: '16px', paddingTop: '16px', display: 'flex', justifyContent: 'space-between' }}>
              <strong style={{ fontSize: '1.1rem' }}>Total</strong>
              <strong style={{ fontSize: '1.1rem', color: 'var(--primary)' }}>{formatPrice(order.total)}</strong>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
