import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  // Required for optimized Docker production image (standalone server.js)
  output: "standalone",
};

export default nextConfig;
