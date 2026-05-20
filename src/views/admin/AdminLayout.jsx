import { Link, Outlet, useLocation } from 'react-router-dom';
import { BsGrid, BsBox, BsTags, BsPeople, BsReceipt } from 'react-icons/bs';
import './AdminPages.css';

const navItems = [
  { path: '/admin', icon: BsGrid, label: 'Dashboard', exact: true },
  { path: '/admin/products', icon: BsBox, label: 'Products' },
  { path: '/admin/categories', icon: BsTags, label: 'Categories' },
  { path: '/admin/orders', icon: BsReceipt, label: 'Orders' },
  { path: '/admin/users', icon: BsPeople, label: 'Users' },
];

export default function AdminLayout() {
  const location = useLocation();

  return (
    <div className="admin-layout">
      <aside className="admin-sidebar">
        <div className="admin-sidebar__header">
          <h3>Admin Panel</h3>
        </div>
        <nav className="admin-sidebar__nav">
          {navItems.map(item => {
            const active = item.exact ? location.pathname === item.path : location.pathname.startsWith(item.path);
            return (
              <Link key={item.path} to={item.path} className={`admin-sidebar__link ${active ? 'admin-sidebar__link--active' : ''}`}>
                <item.icon /> {item.label}
              </Link>
            );
          })}
        </nav>
        <div className="admin-sidebar__footer">
          <Link to="/" className="admin-sidebar__link">← Back to Store</Link>
        </div>
      </aside>
      <main className="admin-main">
        <Outlet />
      </main>
    </div>
  );
}
