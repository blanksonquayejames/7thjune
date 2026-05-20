import { useState, useEffect } from 'react';
import { getAllOrders, updateOrderStatus } from '../../services/api';
import { formatPrice, getStatusBadgeClass } from '../../utils/helpers';
import './AdminPages.css';

export default function AdminOrdersPage() {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    document.title = 'Orders - Admin';
    getAllOrders().then(setOrders).finally(() => setLoading(false));
  }, []);

  const handleStatusChange = async (orderId, status) => {
    await updateOrderStatus(orderId, status);
    setOrders(prev => prev.map(o => o.id === orderId ? { ...o, status } : o));
  };

  if (loading) return <div className="loading-screen"><div className="spinner" /></div>;

  return (
    <div>
      <h1 className="admin-page-title">Orders ({orders.length})</h1>
      <table className="admin-table">
        <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
        <tbody>
          {orders.map(o => (
            <tr key={o.id}>
              <td><strong>#{o.id}</strong></td>
              <td>{o.user?.name}</td>
              <td style={{ fontWeight: 600, color: 'var(--primary)' }}>{formatPrice(o.total)}</td>
              <td><span className={`badge ${getStatusBadgeClass(o.status)}`}>{o.status}</span></td>
              <td style={{ color: 'var(--gray)', fontSize: '.85rem' }}>{new Date(o.created_at).toLocaleDateString()}</td>
              <td>
                <select className="form-select" style={{ padding: '6px 10px', fontSize: '.82rem', maxWidth: '140px' }} value={o.status} onChange={e => handleStatusChange(o.id, e.target.value)}>
                  <option value="pending">Pending</option>
                  <option value="processing">Processing</option>
                  <option value="shipped">Shipped</option>
                  <option value="delivered">Delivered</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
