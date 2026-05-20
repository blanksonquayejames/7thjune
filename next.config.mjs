/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  images: {
    unoptimized: true, // Disable standard image optimization to avoid hosting external domain image proxy overhead in dev/prod
  },
};

export default nextConfig;
