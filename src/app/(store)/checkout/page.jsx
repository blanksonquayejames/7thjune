"use client";
import CheckoutPage from "../../../views/CheckoutPage";
import { ProtectedRoute } from "../../../components/common/RouteGuards";

export default function CheckoutRoute() {
  return (
    <ProtectedRoute>
      <CheckoutPage />
    </ProtectedRoute>
  );
}
