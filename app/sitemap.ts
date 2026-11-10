import type { MetadataRoute } from "next";
import { siteUrl } from "@/lib/get-content";

const ROUTES = ["", "/about", "/expertise", "/experience", "/research", "/conferences", "/patient-information", "/contact"];

export default function sitemap(): MetadataRoute.Sitemap {
  return ROUTES.map((r) => ({ url: `${siteUrl}${r}`, changeFrequency: "monthly", priority: r ? 0.8 : 1 }));
}
