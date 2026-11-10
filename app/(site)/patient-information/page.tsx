import type { Metadata } from "next";
import Image from "next/image";
import { Plus } from "lucide-react";
import { getContent } from "@/lib/get-content";
import { Disclaimer, Eyebrow, PageHero } from "@/components/ui";
import { Reveal } from "@/components/motion";

export const metadata: Metadata = {
  title: "Patient Information — Bladder Cancer, Robotic Cystectomy & Urinary Diversion",
  description:
    "Clear, reassuring information about bladder cancer, robotic surgery, robotic cystectomy, ileal conduits and orthotopic neobladders, with answers to common questions.",
  alternates: { canonical: "/patient-information" },
};

export default async function PatientInformationPage() {
  const { patientArticles, faqs } = await getContent();

  const faqJsonLd = {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    mainEntity: faqs.map((f) => ({
      "@type": "Question",
      name: f.question,
      acceptedAnswer: { "@type": "Answer", text: f.answer },
    })),
  };

  return (
    <>
      <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(faqJsonLd) }} />
      <PageHero
        index="07"
        eyebrow="Patient Information"
        title={
          <>
            Understanding your <span className="italic text-bronze">options.</span>
          </>
        }
        lead="Clear, general information to help you prepare for conversations with your clinical team. It does not replace individual medical advice."
      />

      <div className="container-x">
        <Disclaimer className="max-w-3xl" />
      </div>

      <div className="container-x grid gap-16 py-20 lg:grid-cols-12 lg:py-28">
        <nav aria-label="Topics" className="lg:col-span-3">
          <div className="lg:sticky lg:top-32">
            <p className="eyebrow text-bronze">Topics</p>
            <ol className="mt-5 space-y-1 border-l border-midnight/10">
              {[...patientArticles, { slug: "faq", title: "Frequently Asked Questions" }].map((a, i) => (
                <li key={a.slug}>
                  <a
                    href={`#${a.slug}`}
                    className="-ml-px flex gap-4 border-l border-transparent py-2 pl-5 text-sm text-charcoal/60 transition-colors hover:border-bronze hover:text-midnight"
                  >
                    <span className="font-serif italic text-bronze">{String(i + 1).padStart(2, "0")}</span>
                    {a.title}
                  </a>
                </li>
              ))}
            </ol>
          </div>
        </nav>

        <div className="space-y-24 lg:col-span-8 lg:col-start-5">
          {patientArticles.map((a, i) => (
            <Reveal as="article" key={a.slug} className="scroll-mt-32" id={a.slug}>
              <p className="font-serif text-sm italic text-bronze">{String(i + 1).padStart(2, "0")}</p>
              <h2 className="mt-2 text-4xl leading-tight text-midnight md:text-5xl">{a.title}</h2>
              {a.image && (
                <figure className="mt-8 bg-white p-6">
                  <Image src={a.image} alt={a.imageAlt} width={640} height={360} className="mx-auto h-auto w-full max-w-md" />
                  {a.imageAlt && <figcaption className="mt-4 text-center text-xs text-charcoal/55">Illustration: how a robotic operating theatre is arranged.</figcaption>}
                </figure>
              )}
              <div className="prose-editorial mt-8 text-lg leading-relaxed text-charcoal/80">
                {a.body.map((p) => (
                  <p key={p}>{p}</p>
                ))}
              </div>
            </Reveal>
          ))}

          <section id="faq" className="scroll-mt-32">
            <Reveal>
              <Eyebrow>FAQ</Eyebrow>
              <h2 className="mt-6 text-4xl leading-tight text-midnight md:text-5xl">Frequently Asked Questions</h2>
            </Reveal>
            <div className="mt-10 bg-white px-6 md:px-8">
              {faqs.map((f) => (
                <details key={f.question} className="group border-b border-midnight/10">
                  <summary className="flex cursor-pointer list-none items-center justify-between gap-6 py-6 font-serif text-2xl text-midnight [&::-webkit-details-marker]:hidden">
                    {f.question}
                    <Plus aria-hidden className="size-5 shrink-0 text-bronze transition-transform duration-500 group-open:rotate-45" strokeWidth={1.25} />
                  </summary>
                  <p className="max-w-2xl pb-8 leading-relaxed text-charcoal/75">{f.answer}</p>
                </details>
              ))}
            </div>
          </section>
        </div>
      </div>
    </>
  );
}
