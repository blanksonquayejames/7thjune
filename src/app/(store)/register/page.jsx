"use client";
import { Suspense } from 'react';
import RegisterPage from "../../../views/RegisterPage";
import { GuestRoute } from "../../../components/common/RouteGuards";

export default function RegisterRoute() {
  return (
    <Suspense fallback={<div className="loading-screen"><div className="spinner" /></div>}>
      <GuestRoute>
        <RegisterPage />
      </GuestRoute>
    </Suspense>
  );
}
