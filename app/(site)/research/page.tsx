import type { Metadata } from "next";
import { ArrowUpRight } from "lucide-react";
import { getContent } from "@/lib/get-content";
import { Eyebrow, PageHero, Placeholder } from "@/components/ui";
import { Reveal } from "@/components/motion";

export const metadata: Metadata = {
  title: "Research & Publications",
  description: "Peer-reviewed publications and academic contributions of Dr. Malek Gaashan, Consultant Urological Surgeon.",
  alternates: { canonical: "/research" },
};

export default async function ResearchPage() {
  const { publications, academicContributions } = await getContent();

  return (
    <>
      <PageHero
        index="05"
        eyebrow="Research & Publications"
        title={
          <>
            An academic <span className="italic text-bronze">portfolio.</span>
          </>
        }
        lead="Contributions to the urological literature, from rare oncological presentations to refinements in laparoscopic technique."
      />

      <section className="bg-ivory py-24 md:py-32">
        <div className="container-x">
          <Reveal>
            <Eyebrow index="I">Publications</Eyebrow>
          </Reveal>
          <ol className="mt-12">
            {publications.map((p, i) => {
              const href = p.url || (p.doi ? `https://doi.org/${p.doi}` : "");
              return (
                <Reveal as="li" key={p.title} delay={i * 0.08} className="mb-6 grid gap-6 bg-white p-8 md:grid-cols-12 md:gap-10 md:p-12">
                  <p className="font-serif text-5xl text-bronze md:col-span-2">{p.year}</p>
                  <div className="md:col-span-8">
                    <h2 className="text-3xl leading-snug text-midnight md:text-[2.1rem]">{p.title}</h2>
                    <dl className="mt-6 grid gap-x-10 gap-y-3 text-sm sm:grid-cols-2">
                      <div>
                        <dt className="eyebrow text-charcoal/50">Journal</dt>
                        <dd className="mt-1 text-charcoal/80">{p.journal || <Placeholder>journal to be verified</Placeholder>}</dd>
                      </div>
                      <div>
                        <dt className="eyebrow text-charcoal/50">DOI</dt>
                        <dd className="mt-1 break-all text-charcoal/80">{p.doi || <Placeholder>DOI to be added</Placeholder>}</dd>
                      </div>
                    </dl>
                  </div>
                  <div className="md:col-span-2 md:text-right">
                    {href && (
                      <a
                        href={href}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="inline-flex items-center gap-2 text-[0.7rem] font-semibold uppercase tracking-[0.2em] text-midnight"
                      >
                        <span className="link-underline">Read</span>
                        <ArrowUpRight aria-hidden className="size-4 text-bronze" />
                        <span className="sr-only">{p.title} (opens in a new tab)</span>
                      </a>
                    )}
                  </div>
                </Reveal>
              );
            })}
          </ol>
        </div>
      </section>

      <section className="bg-sand py-28 md:py-36">
        <div className="container-x grid gap-12 md:grid-cols-12">
          <Reveal className="md:col-span-5">
            <Eyebrow index="II">Forthcoming</Eyebrow>
            <h2 className="mt-6 text-4xl leading-tight text-midnight md:text-5xl">
              Future publications & <span className="italic">academic contributions</span>
            </h2>
          </Reveal>
          <div className="md:col-span-6 md:col-start-7">
            {academicContributions.length ? (
              <ul className="border-t border-midnight/15">
                {academicContributions.map((c) => (
                  <li key={c} className="border-b border-midnight/10 py-5 font-serif text-xl text-midnight">
                    {c}
                  </li>
                ))}
              </ul>
            ) : (
              <p className="leading-relaxed text-charcoal/70">
                New publications, book chapters, peer-review activity and academic contributions will be listed here as
                they are confirmed.
              </p>
            )}
          </div>
        </div>
      </section>
    </>
  );
}
