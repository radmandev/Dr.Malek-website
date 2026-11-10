import type { Metadata } from "next";
import { getContent } from "@/lib/get-content";
import { ButtonLink, Disclaimer, Eyebrow, PageHero, Photo } from "@/components/ui";
import { Reveal } from "@/components/motion";

export const metadata: Metadata = {
  title: "Surgical Expertise — Robotic Cystectomy & Intracorporeal Urinary Diversion",
  description:
    "Robotic radical cystectomy, intracorporeal ileal conduit and neobladder reconstruction, complex bladder reconstruction, uro-oncology and organ-sparing approaches.",
  alternates: { canonical: "/expertise" },
};

export default async function ExpertisePage() {
  const { expertise } = await getContent();
  const heroImage = expertise.find((e) => e.image);

  return (
    <>
      <PageHero
        index="03"
        eyebrow="Surgical Expertise"
        title={
          <>
            Advanced robotic surgery, <span className="italic text-bronze">planned around the individual.</span>
          </>
        }
        lead="Specialist practice in robotic bladder cancer surgery and reconstruction of the urinary tract — delivered within a multidisciplinary team and grounded in current evidence."
        image={heroImage?.image}
        imageAlt={heroImage?.imageLabel}
      />

      <div className="container-x grid gap-16 pb-24 pt-8 lg:grid-cols-12 lg:pb-32">
        <nav aria-label="Areas of expertise" className="hidden lg:col-span-3 lg:block">
          <ol className="sticky top-32 space-y-1 border-l border-midnight/10">
            {expertise.map((e, i) => (
              <li key={e.slug}>
                <a
                  href={`#${e.slug}`}
                  className="-ml-px flex gap-4 border-l border-transparent py-2.5 pl-5 text-sm text-charcoal/60 transition-colors hover:border-bronze hover:text-midnight"
                >
                  <span className="font-serif italic text-bronze">0{i + 1}</span>
                  {e.title}
                </a>
              </li>
            ))}
          </ol>
        </nav>

        <div className="space-y-32 lg:col-span-9">
          {expertise.map((e, i) => (
            <article key={e.slug} id={e.slug} className="scroll-mt-32">
              <Reveal>
                <Eyebrow index={`0${i + 1}`}>Expertise</Eyebrow>
                <h2 className="mt-6 text-4xl leading-[1.05] text-midnight md:text-6xl">{e.title}</h2>
                <p className="mt-6 max-w-2xl font-serif text-2xl italic leading-snug text-charcoal/70">{e.summary}</p>
              </Reveal>
              <div className="mt-12 grid gap-12 md:grid-cols-5">
                <Reveal className="md:col-span-3">
                  <div className="prose-editorial leading-relaxed text-charcoal/80">
                    {e.body.map((p) => (
                      <p key={p}>{p}</p>
                    ))}
                  </div>
                </Reveal>
                <Reveal delay={0.1} className="md:col-span-2">
                  {e.image && (
                    <Photo src={e.image} alt={e.title} label={e.imageLabel} position={e.imagePosition} zoom={e.imageZoom} className="mb-6 aspect-[4/3]" sizes="(min-width: 1024px) 28vw, (min-width: 768px) 40vw, 100vw" />
                  )}
                  <div className="bg-white p-7">
                  <p className="eyebrow text-bronze">Key elements</p>
                  <ul className="mt-5 border-t border-midnight/15">
                    {e.points.map((pt) => (
                      <li key={pt} className="border-b border-midnight/10 py-3.5 font-serif text-xl text-midnight">
                        {pt}
                      </li>
                    ))}
                  </ul>
                  </div>
                </Reveal>
              </div>
            </article>
          ))}

          <Reveal>
            <Disclaimer />
            <div className="mt-12 flex flex-wrap gap-4">
              <ButtonLink href="/patient-information" variant="dark">
                Patient Information
              </ButtonLink>
            </div>
          </Reveal>
        </div>
      </div>
    </>
  );
}
