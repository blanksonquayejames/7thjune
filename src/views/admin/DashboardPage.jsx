import { useState, useEffect } from 'react';
import Link from 'next/link';
import { BsPlus } from 'react-icons/bs';
import { getAllOrders, getAllProducts, getAllUsers } from '../../services/api';
import { formatPrice, getStatusBadgeClass } from '../../utils/helpers';
import './AdminPages.css';

export default function DashboardPage() {
  const [orders, setOrders] = useState([]);
  const [products, setProducts] = useState([]);
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);

  const load = async () => {
    setLoading(true);
    try {
      const [ords, prods, usrs] = await Promise.all([getAllOrders(), getAllProducts(), getAllUsers()]);
      setOrders(ords || []);
      setProducts(prods || []);
      setUsers(usrs || []);
    } catch (err) {
      console.error(err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    document.title = 'Admin Dashboard - 7th June Computers';
    load();
  }, []);

  if (loading) return <div className="loading-screen"><div className="spinner" /></div>;

  const totalRevenue = orders.filter(o => o.status !== 'cancelled').reduce((sum, o) => sum + Number(o.total || 0), 0);
  const activeOrders = orders.filter(o => o.status === 'processing' || o.status === 'shipped').length;
  const productsInStock = products.reduce((acc, p) => acc + (Number(p.stock) || 0), 0);

  return (
    <div>
      <div className="admin-header-row">
        <h1 className="admin-page-title">Overview</h1>
        <div>
          <Link href="/admin/products" className="btn btn--primary btn--sm">
            <BsPlus style={{ marginRight: 8 }} /> Add Product
          </Link>
        </div>
      </div>

      <div className="admin-stat-grid">
        <div className="admin-stat">
          <div className="admin-stat__label">Total Revenue</div>
          <div className="admin-stat__value">{formatPrice(totalRevenue)}</div>
        </div>
        <div className="admin-stat">
          <div className="admin-stat__label">Active Orders</div>
          <div className="admin-stat__value">{activeOrders}</div>
        </div>
        <div className="admin-stat">
          <div className="admin-stat__label">Total Customers</div>
          <div className="admin-stat__value">{users.length}</div>
        </div>
        <div className="admin-stat">
          <div className="admin-stat__label">Products in Stock</div>
          <div className="admin-stat__value">{productsInStock}</div>
        </div>
      </div>

      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: 20 }}>
        <div>
          <h3 style={{ marginBottom: 12 }}>Recent Orders</h3>
          <table className="admin-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Status</th>
                <th style={{ textAlign: 'right' }}>Total</th>
              </tr>
            </thead>
            <tbody>
              {orders.slice(0, 6).map(o => (
                <tr key={o.id}>
                  <td><strong>{o.id}</strong></td>
                  <td>{o.user?.name || o.customer || '—'}</td>
                  <td><span className={`badge ${getStatusBadgeClass(o.status)}`}>{o.status}</span></td>
                  <td style={{ textAlign: 'right', fontWeight: 700 }}>{formatPrice(o.total)}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <div>
          <h3 style={{ marginBottom: 12 }}>Low Stock Alerts</h3>
          <div className="admin-table" style={{ padding: 12 }}>
            {products.filter(p => p.stock < 10).length === 0 ? (
              <div style={{ padding: 20 }}>No low stock products</div>
            ) : (
              products.filter(p => p.stock < 10).map(p => (
                <div key={p.id} style={{ display: 'flex', justifyContent: 'space-between', padding: '12px 8px', borderBottom: '1px solid rgba(0,0,0,.04)' }}>
                  <div>
                    <div style={{ fontWeight: 700 }}>{p.name}</div>
                    <div style={{ fontSize: '.85rem', color: 'var(--gray)' }}>{p.category?.name || p.category}</div>
                  </div>
                  <div style={{ textAlign: 'right' }}>
                    <div style={{ fontWeight: 700, color: p.stock === 0 ? 'var(--danger)' : 'var(--warning)' }}>{p.stock}</div>
                    <div style={{ fontSize: '.78rem', color: 'var(--gray)' }}>Remaining</div>
                  </div>
                </div>
              ))
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
