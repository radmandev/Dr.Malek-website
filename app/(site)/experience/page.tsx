import type { Metadata } from "next";
import { ShieldCheck } from "lucide-react";
import { getContent } from "@/lib/get-content";
import { ButtonLink, Eyebrow, PageHero, Photo } from "@/components/ui";
import { Counter, Reveal } from "@/components/motion";

export const metadata: Metadata = {
  title: "Experience & Achievements — 50+ Robotic Cystectomies",
  description:
    "Dr. Malek Gaashan's robotic cystectomy milestones, including 50+ robotic cystectomies with intracorporeal urinary diversion at University Hospitals of Leicester.",
  alternates: { canonical: "/experience" },
};

export default async function ExperiencePage() {
  const { achievements, stats } = await getContent();
  const lead = stats[0];
  const hero = achievements.cases.find((c) => c.image.includes("team")) ?? achievements.cases[0];

  return (
    <>
      <PageHero
        index="04"
        eyebrow="Experience & Achievements"
        title={
          <>
            A practice measured in <span className="italic text-bronze">precision.</span>
          </>
        }
        image={hero?.image}
        imageAlt={hero?.caption}
      />

      {/* FEATURE */}
      <section className="bg-ivory py-28 md:py-36">
        <div className="container-x grid items-end gap-16 lg:grid-cols-12">
          <Reveal className="lg:col-span-7">
            <p className="font-serif text-[10rem] leading-[0.78] tracking-tight text-midnight sm:text-[14rem] lg:text-[17rem]">
              <Counter value={lead?.value ?? 50} suffix={lead?.suffix ?? "+"} />
            </p>
          </Reveal>
          <div className="lg:col-span-5">
            <Reveal>
              <Eyebrow index="I">Milestone</Eyebrow>
              <h2 className="mt-6 text-4xl leading-tight text-midnight md:text-5xl">{achievements.headline}</h2>
              <p className="mt-6 font-serif text-2xl italic leading-snug text-charcoal/70">{achievements.statement}</p>
              {achievements.reflection && (
                <figure className="mt-10 border-l-2 border-champagne pl-6">
                  <blockquote className="leading-relaxed text-charcoal/80">“{achievements.reflection}”</blockquote>
                  <figcaption className="eyebrow mt-3 text-bronze">Dr. Malek Gaashan</figcaption>
                </figure>
              )}
            </Reveal>
          </div>
        </div>
      </section>

      {/* MILESTONES */}
      <section className="bg-sand py-28 md:py-36">
        <div className="container-x relative">
          <Reveal>
            <Eyebrow index="II">
              Selected Milestones
            </Eyebrow>
          </Reveal>
          <ol className="mt-14 grid gap-6 md:grid-cols-2">
            {achievements.milestones.map((m, i) => (
              <Reveal as="li" key={m.title} delay={(i % 2) * 0.1} className="group h-full bg-white p-8 transition-shadow duration-700 hover:shadow-[0_30px_60px_-30px_rgba(16,29,44,0.3)] md:p-12">
                  <span className="font-serif text-5xl italic text-bronze">0{i + 1}</span>
                  <p className="mt-10 font-serif text-3xl leading-tight text-midnight">{m.title}</p>
                  <p className="mt-4 max-w-md text-sm leading-relaxed text-charcoal/65">{m.detail}</p>
              </Reveal>
            ))}
          </ol>
        </div>
      </section>

      {/* TAILORED APPROACHES */}
      {achievements.approaches.length > 0 && (
        <section className="bg-paper py-28 md:py-36">
          <div className="container-x grid gap-16 lg:grid-cols-12">
            <Reveal className="lg:col-span-5">
              <Eyebrow index="III">Tailored Approaches</Eyebrow>
              <h2 className="mt-6 text-4xl leading-tight text-midnight md:text-5xl">
                Three patients. <span className="italic text-bronze">Three approaches.</span>
              </h2>
              <p className="mt-6 leading-relaxed text-charcoal/70">{achievements.approachesIntro}</p>
            </Reveal>
            <ol className="lg:col-span-6 lg:col-start-7">
              {achievements.approaches.map((a, i) => (
                <Reveal as="li" key={a.title} delay={i * 0.08} className="mb-4 grid grid-cols-[3rem_1fr] gap-4 bg-white p-7">
                  <span className="font-serif text-3xl italic text-bronze">{i + 1}</span>
                  <div>
                    <p className="font-serif text-2xl leading-snug text-midnight">{a.title}</p>
                    <p className="mt-2 text-sm leading-relaxed text-charcoal/65">{a.detail}</p>
                  </div>
                </Reveal>
              ))}
              <p className="mt-6 text-xs leading-relaxed text-charcoal/50">
                Described in general terms only. Suitability for any approach depends on individual assessment.
              </p>
            </ol>
          </div>
        </section>
      )}

      {/* TEAM QUOTE */}
      {achievements.teamQuote && (
        <section className="border-y border-midnight/10 bg-white py-24 text-midnight md:py-32">
          <Reveal className="container-x text-center">
            <span aria-hidden className="font-serif text-7xl leading-none text-champagne">“</span>
            <blockquote className="mx-auto mt-2 max-w-4xl font-serif text-3xl italic leading-snug md:text-5xl">
              {achievements.teamQuote}
            </blockquote>
            <p className="eyebrow mt-8 text-bronze">Dr. Malek Gaashan</p>
          </Reveal>
        </section>
      )}

      {/* CASE STUDY IMAGERY */}
      <section className="py-28 md:py-36">
        <div className="container-x">
          <div className="grid gap-10 md:grid-cols-12">
            <Reveal className="md:col-span-6">
              <Eyebrow index="IV">Visual Case Studies</Eyebrow>
              <h2 className="mt-6 text-4xl leading-tight text-midnight md:text-5xl">
                Inside robotic <span className="italic">practice</span>.
              </h2>
            </Reveal>
            <Reveal delay={0.1} className="md:col-span-5 md:col-start-8">
              <p className="flex gap-4 text-sm leading-relaxed text-charcoal/70">
                <ShieldCheck aria-hidden className="mt-0.5 size-5 shrink-0 text-bronze" strokeWidth={1.25} />
                Only anonymised, approved surgical and educational imagery is shown. No identifiable patient information
                or confidential clinical photographs are ever published.
              </p>
            </Reveal>
          </div>
          <div className="mt-16 grid gap-8 md:grid-cols-2">
            {achievements.cases.map((c, i) => (
              <Reveal key={c.title + i} delay={i * 0.1} className={i % 2 ? "md:mt-20" : ""}>
                <figure>
                  <Photo src={c.image} alt={c.title} label={c.title} className="aspect-[4/3]" sizes="(min-width: 768px) 50vw, 100vw" />
                  <figcaption className="mt-5">
                    <p className="font-serif text-2xl text-midnight">{c.title}</p>
                    <p className="mt-1 text-sm text-charcoal/60">{c.caption}</p>
                  </figcaption>
                </figure>
              </Reveal>
            ))}
          </div>
          <div className="mt-20">
            <ButtonLink href="/conferences" variant="dark">
              Conferences & Education
            </ButtonLink>
          </div>
        </div>
      </section>
    </>
  );
}
