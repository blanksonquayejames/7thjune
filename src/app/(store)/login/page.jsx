"use client";
import { Suspense } from 'react';
import LoginPage from "../../../views/LoginPage";
import { GuestRoute } from "../../../components/common/RouteGuards";

export default function LoginRoute() {
  return (
    <Suspense fallback={<div className="loading-screen"><div className="spinner" /></div>}>
      <GuestRoute>
        <LoginPage />
      </GuestRoute>
    </Suspense>
  );
}
