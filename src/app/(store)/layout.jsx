"use client";
import Navbar from "../../components/layout/Navbar";
import Footer from "../../components/layout/Footer";
import ToastContainer from "../../components/layout/ToastContainer";
import { useCart } from "../../context/CartContext";

export default function StoreLayout({ children }) {
  const { toasts, removeToast } = useCart();

  return (
    <div className="app-layout">
      <Navbar />
      <main className="app-main">
        {children}
      </main>
      <Footer />
      <ToastContainer toasts={toasts} removeToast={removeToast} />
    </div>
  );
}
