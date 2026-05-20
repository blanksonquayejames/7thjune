"use client";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { BsGrid, BsBox, BsTags, BsPeople, BsReceipt } from "react-icons/bs";
import { AdminRoute } from "../../components/common/RouteGuards";
import "../../views/admin/AdminPages.css";

const navItems = [
  { path: "/admin", icon: BsGrid, label: "Dashboard", exact: true },
  { path: "/admin/products", icon: BsBox, label: "Products" },
  { path: "/admin/categories", icon: BsTags, label: "Categories" },
  { path: "/admin/orders", icon: BsReceipt, label: "Orders" },
  { path: "/admin/users", icon: BsPeople, label: "Users" },
];

export default function NextAdminLayout({ children }) {
  const pathname = usePathname();

  return (
    <AdminRoute>
      <div className="admin-layout">
        <aside className="admin-sidebar">
          <div className="admin-sidebar__header">
            <h3>Admin Panel</h3>
          </div>
          <nav className="admin-sidebar__nav">
            {navItems.map(item => {
              const active = item.exact ? pathname === item.path : pathname.startsWith(item.path);
              return (
                <Link key={item.path} href={item.path} className={`admin-sidebar__link ${active ? 'admin-sidebar__link--active' : ''}`}>
                  <item.icon /> {item.label}
                </Link>
              );
            })}
          </nav>
          <div className="admin-sidebar__footer">
            <Link href="/" className="admin-sidebar__link">← Back to Store</Link>
          </div>
        </aside>
        <main className="admin-main">
          {children}
        </main>
      </div>
    </AdminRoute>
  );
}
