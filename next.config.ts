import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // Images uploaded through /admin live in Supabase Storage.
  images: { remotePatterns: [{ protocol: "https", hostname: "*.supabase.co" }] },
};

export default nextConfig;
