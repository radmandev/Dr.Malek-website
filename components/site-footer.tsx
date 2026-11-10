import Link from "next/link";
import type { SiteContent } from "@/lib/content";

const LINKS = [
  ["About", "/about"],
  ["Surgical Expertise", "/expertise"],
  ["Experience & Achievements", "/experience"],
  ["Research & Publications", "/research"],
  ["Conferences & Education", "/conferences"],
  ["Patient Information", "/patient-information"],
  ["Professional Enquiries", "/contact"],
];

export function SiteFooter({ content }: { content: SiteContent }) {
  const { profile, contact } = content;
  return (
    <footer className="grain relative overflow-hidden bg-midnight text-ivory">
      <div className="container-x relative pb-10 pt-24">
        <div className="grid gap-16 lg:grid-cols-12">
          <div className="lg:col-span-6">
            <p className="font-serif text-5xl leading-[1.05] md:text-6xl">
              Malek <span className="italic text-champagne-light">Gaashan</span>
            </p>
            <p className="mt-4 text-[0.68rem] font-semibold uppercase tracking-[0.28em] text-ivory/50">
              {profile.postnominals}
            </p>
            <p className="mt-8 max-w-md font-serif text-xl italic leading-relaxed text-ivory/65">
              “{profile.philosophy}”
            </p>
          </div>
          <nav aria-label="Footer" className="lg:col-span-3">
            <p className="eyebrow text-champagne">Explore</p>
            <ul className="mt-6 space-y-3 text-sm text-ivory/70">
              {LINKS.map(([label, href]) => (
                <li key={href}>
                  <Link href={href} className="link-underline hover:text-ivory">
                    {label}
                  </Link>
                </li>
              ))}
            </ul>
          </nav>
          <div className="text-sm text-ivory/70 lg:col-span-3">
            <p className="eyebrow text-champagne">Practice</p>
            <p className="mt-6 leading-relaxed">
              {contact.department}
              <br />
              {contact.institution}
            </p>
            {contact.linkedin && (
              <a href={contact.linkedin} target="_blank" rel="noopener noreferrer" className="link-underline mt-6 inline-block hover:text-ivory">
                LinkedIn ↗
              </a>
            )}
          </div>
        </div>
        <div className="mt-20 flex flex-col gap-4 border-t border-ivory/10 pt-8 text-xs leading-relaxed text-ivory/40 md:flex-row md:justify-between">
          <p>© {new Date().getFullYear()} {profile.name}. All rights reserved.</p>
          <p className="max-w-2xl md:text-right">
            General information only — not a substitute for individual medical advice. This website does not provide
            emergency services; in an emergency call 999 (UK) or your local emergency number.
          </p>
        </div>
      </div>
    </footer>
  );
}
