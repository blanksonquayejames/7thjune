import { useState, useEffect } from 'react';
import { getAllUsers } from '../../services/api';
import './AdminPages.css';

export default function AdminUsersPage() {
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    document.title = 'Users - Admin';
    getAllUsers().then(setUsers).finally(() => setLoading(false));
  }, []);

  if (loading) return <div className="loading-screen"><div className="spinner" /></div>;

  return (
    <div>
      <h1 className="admin-page-title">Users ({users.length})</h1>
      <table className="admin-table">
        <thead><tr><th>Name</th><th>Email</th><th>Role</th></tr></thead>
        <tbody>
          {users.map(u => (
            <tr key={u.id}>
              <td><strong>{u.name}</strong></td>
              <td style={{ color: 'var(--gray)' }}>{u.email}</td>
              <td><span className={`badge ${u.role === 'admin' ? 'badge--processing' : 'badge--pending'}`}>{u.role}</span></td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
