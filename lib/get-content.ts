import "server-only";
import { cache } from "react";
import { defaultContent, type SiteContent } from "./content";

export const supabaseUrl = process.env.NEXT_PUBLIC_SUPABASE_URL;
export const supabaseAnonKey = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY;
export const siteUrl = process.env.NEXT_PUBLIC_SITE_URL ?? "http://localhost:3000";

// Reads CMS overrides from Supabase (public, RLS-protected read) and merges them over the defaults.
// Without Supabase env vars the site renders the bundled default content.
export const getContent = cache(async (): Promise<SiteContent> => {
  if (!supabaseUrl || !supabaseAnonKey) return defaultContent;
  try {
    const res = await fetch(`${supabaseUrl}/rest/v1/site_content?select=key,value`, {
      headers: { apikey: supabaseAnonKey, Authorization: `Bearer ${supabaseAnonKey}` },
      next: { revalidate: 60, tags: ["content"] },
    });
    if (!res.ok) throw new Error(`Supabase ${res.status}`);
    const rows: { key: string; value: unknown }[] = await res.json();
    const merged: Record<string, unknown> = { ...defaultContent };
    for (const r of rows) if (r.key in defaultContent) merged[r.key] = r.value;
    return merged as SiteContent;
  } catch (e) {
    console.error("CMS fetch failed, using defaults:", e);
    return defaultContent;
  }
});
