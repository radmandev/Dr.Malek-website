import { SiteHeader } from "@/components/site-header";
import { SiteFooter } from "@/components/site-footer";
import { getContent } from "@/lib/get-content";

export default async function SiteLayout({ children }: { children: React.ReactNode }) {
  const content = await getContent();
  return (
    <>
      <SiteHeader expertise={content.expertise.map(({ slug, title }) => ({ slug, title }))} />
      <main id="main">{children}</main>
      <SiteFooter content={content} />
    </>
  );
}
