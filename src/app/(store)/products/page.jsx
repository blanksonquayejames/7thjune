"use client";
import { Suspense } from 'react';
import ProductsPage from "../../../views/ProductsPage";

export default function ProductsRoute() {
  return (
    <Suspense fallback={<div className="loading-screen"><div className="spinner" /></div>}>
      <ProductsPage />
    </Suspense>
  );
}
