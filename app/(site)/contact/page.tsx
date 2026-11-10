import type { Metadata } from "next";
import Image from "next/image";
import { getContent } from "@/lib/get-content";
import { Eyebrow, PageHero, Placeholder } from "@/components/ui";
import { Reveal } from "@/components/motion";
import { ContactForm } from "@/components/contact-form";

export const metadata: Metadata = {
  title: "Professional Enquiries & Contact",
  description: "Professional, academic and collaboration enquiries for Dr. Malek Gaashan, Consultant Urological Surgeon.",
  alternates: { canonical: "/contact" },
};

export default async function ContactPage() {
  const { contact, profile } = await getContent();
  const rows: [string, React.ReactNode][] = [
    ["Institution", contact.institution],
    ["Department", contact.department],
    ["Address", contact.address || <Placeholder key="a">verified address to be added</Placeholder>],
    [
      "Email",
      contact.email ? (
        <a key="e" href={`mailto:${contact.email}`} className="link-underline">
          {contact.email}
        </a>
      ) : (
        <Placeholder key="e">verified professional email to be added</Placeholder>
      ),
    ],
    ["Secretary", contact.secretaryPhone || <Placeholder key="p">verified number to be added</Placeholder>],
    [
      "LinkedIn",
      contact.linkedin ? (
        <a key="l" href={contact.linkedin} target="_blank" rel="noopener noreferrer" className="link-underline">
          View professional profile ↗
        </a>
      ) : (
        <Placeholder key="l">LinkedIn profile URL to be added</Placeholder>
      ),
    ],
    ["GMC", contact.gmcNumber || <Placeholder key="g">GMC reference to be added</Placeholder>],
  ];

  return (
    <>
      <PageHero
        index="08"
        eyebrow="Contact"
        title={
          <>
            Professional Enquiries <span className="italic text-bronze">& Contact</span>
          </>
        }
        lead="For professional, academic, media and collaboration enquiries."
      />

      <section className="bg-ivory py-24 md:py-32">
        <div className="container-x grid gap-20 lg:grid-cols-12">
          <div className="lg:col-span-7">
            <Reveal>
              <Eyebrow index="I">Send an enquiry</Eyebrow>
            </Reveal>
            <Reveal delay={0.1}>
              <ContactForm />
            </Reveal>
          </div>
          <aside className="lg:col-span-4 lg:col-start-9">
            <Reveal>
              {profile.avatar && (
                <div className="mb-12 flex items-center gap-5">
                  <Image src={profile.avatar} alt={`Dr. Malek Gaashan`} width={80} height={80} className="size-20 rounded-full object-cover ring-1 ring-champagne ring-offset-4 ring-offset-ivory" />
                  <div>
                    <p className="font-serif text-2xl text-midnight">{profile.name}</p>
                    <p className="text-xs uppercase tracking-[0.2em] text-bronze">{profile.postnominals}</p>
                  </div>
                </div>
              )}
              <Eyebrow index="II">Practice details</Eyebrow>
              <dl className="mt-10 border-t border-midnight/15">
                {rows.map(([k, v]) => (
                  <div key={k} className="grid grid-cols-[7rem_1fr] gap-4 border-b border-midnight/10 py-5 text-sm">
                    <dt className="eyebrow pt-0.5 text-charcoal/50">{k}</dt>
                    <dd className="text-midnight">{v}</dd>
                  </div>
                ))}
              </dl>
            </Reveal>
            <Reveal delay={0.1}>
              <div className="mt-12 border-l-2 border-champagne bg-white p-8 text-sm leading-relaxed text-charcoal/75">
                <p className="eyebrow mb-3 text-bronze">Please note</p>
                <p>
                  This form is not monitored for clinical or urgent matters and cannot be used to book appointments.
                  Please do not include confidential medical information. For medical advice, contact your GP or
                  treating team; in an emergency call 999 (UK) or your local emergency number.
                </p>
              </div>
            </Reveal>
          </aside>
        </div>
      </section>
    </>
  );
}
