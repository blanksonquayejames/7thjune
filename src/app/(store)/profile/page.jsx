"use client";
import ProfilePage from "../../../views/ProfilePage";
import { ProtectedRoute } from "../../../components/common/RouteGuards";

export default function ProfileRoute() {
  return (
    <ProtectedRoute>
      <ProfilePage />
    </ProtectedRoute>
  );
}
