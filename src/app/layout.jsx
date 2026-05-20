import "../index.css";
import { AuthProvider } from "../context/AuthContext";
import { ConfirmationProvider } from "../context/ConfirmationContext";
import { CartProvider } from "../context/CartContext";

export const metadata = {
  title: "7th June Computers",
  description: "7th June Computers - All the tech you need, all in one place. Discover the latest electronics, computers, networking gear, tablets and peripherals.",
};

export default function RootLayout({ children }) {
  return (
    <html lang="en">
      <head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
        {/* Paystack Inline SDK */}
        <script src="https://js.paystack.co/v2/inline.js" defer></script>
      </head>
      <body>
        <AuthProvider>
          <ConfirmationProvider>
            <CartProvider>
              {children}
            </CartProvider>
          </ConfirmationProvider>
        </AuthProvider>
      </body>
    </html>
  );
}
