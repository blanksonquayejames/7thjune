import { Outlet } from 'react-router-dom';
import Navbar from './Navbar';
import Footer from './Footer';
import { useCart } from '../../context/CartContext';

export default function Layout() {
  const { toast } = useCart();

  return (
    <div className="app-layout">
      <Navbar />
      <main className="app-main">
        <Outlet />
      </main>
      <Footer />
      {toast && (
        <div className={`toast toast--${toast.type}`} id="toast-notification">
          {toast.message}
        </div>
      )}
    </div>
  );
}
