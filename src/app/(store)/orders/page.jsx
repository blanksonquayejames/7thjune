"use client";
import OrdersPage from "../../../views/OrdersPage";
import { ProtectedRoute } from "../../../components/common/RouteGuards";

export default function OrdersRoute() {
  return (
    <ProtectedRoute>
      <OrdersPage />
    </ProtectedRoute>
  );
}
