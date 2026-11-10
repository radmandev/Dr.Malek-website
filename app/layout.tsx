import type { Metadata, Viewport } from "next";
import { Cormorant_Garamond, Manrope } from "next/font/google";
import { siteUrl } from "@/lib/get-content";
import "./globals.css";

const cormorant = Cormorant_Garamond({
  subsets: ["latin"],
  weight: ["300", "400", "500"],
  style: ["normal", "italic"],
  variable: "--font-cormorant",
  display: "swap",
});
const manrope = Manrope({ subsets: ["latin"], variable: "--font-manrope", display: "swap" });

const description =
  "Dr. Malek Gaashan, MD, MRCS, FRCS (Urol) — Consultant Urological Surgeon specialising in robotic radical cystectomy, bladder cancer surgery and intracorporeal urinary diversion at University Hospitals of Leicester.";

export const metadata: Metadata = {
  metadataBase: new URL(siteUrl),
  title: {
    default: "Dr. Malek Gaashan — Consultant Robotic Urological Surgeon",
    template: "%s — Dr. Malek Gaashan",
  },
  description,
  keywords: [
    "Dr Malek Gaashan",
    "Robotic Urology Surgeon",
    "Robotic Cystectomy",
    "Bladder Cancer Surgeon",
    "Intracorporeal Urinary Diversion",
    "Consultant Urological Surgeon UK",
    "Leicester urologist",
  ],
  authors: [{ name: "Dr. Malek Gaashan" }],
  openGraph: {
    type: "website",
    locale: "en_GB",
    siteName: "Dr. Malek Gaashan",
    title: "Dr. Malek Gaashan — Consultant Robotic Urological Surgeon",
    description,
  },
  twitter: { card: "summary_large_image" },
  alternates: { canonical: "/" },
};

export const viewport: Viewport = { themeColor: "#101D2C" };

const jsonLd = {
  "@context": "https://schema.org",
  "@type": "Physician",
  name: "Dr. Malek Gaashan",
  honorificSuffix: "MD, MRCS, FRCS (Urol)",
  url: siteUrl,
  sameAs: ["https://www.linkedin.com/in/malek-gaashan-md-mrcs-frcs-urol-0061a9117"],
  medicalSpecialty: "Urologic",
  description,
  jobTitle: "Consultant Urological Surgeon",
  worksFor: { "@type": "Hospital", name: "University Hospitals of Leicester NHS Trust" },
  alumniOf: [
    { "@type": "CollegeOrUniversity", name: "Mutah University" },
    { "@type": "EducationalOrganization", name: "Royal College of Surgeons in Ireland" },
  ],
  knowsAbout: [
    "Robotic radical cystectomy",
    "Bladder cancer",
    "Intracorporeal urinary diversion",
    "Ileal conduit",
    "Orthotopic neobladder",
    "Uro-oncology",
  ],
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en-GB" className={`${cormorant.variable} ${manrope.variable}`}>
      <body>
        <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }} />
        {children}
      </body>
    </html>
  );
}
