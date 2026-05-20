"use client";
import OrderDetailPage from "../../../../views/OrderDetailPage";
import { ProtectedRoute } from "../../../../components/common/RouteGuards";

export default function OrderDetailRoute() {
  return (
    <ProtectedRoute>
      <OrderDetailPage />
    </ProtectedRoute>
  );
}
