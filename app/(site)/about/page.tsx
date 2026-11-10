import type { Metadata } from "next";
import { getContent } from "@/lib/get-content";
import { ButtonLink, Eyebrow, PageHero, Photo, Placeholder } from "@/components/ui";
import { Reveal } from "@/components/motion";
import { Timeline } from "@/components/timeline";

export const metadata: Metadata = {
  title: "About",
  description:
    "Biography, career, qualifications and awards of Dr. Malek Gaashan, Consultant Urological Surgeon at University Hospitals of Leicester NHS Trust.",
  alternates: { canonical: "/about" },
};

export default async function AboutPage() {
  const { profile, timeline, education, registrations, awards } = await getContent();

  return (
    <>
      <PageHero
        index="02"
        eyebrow="About"
        title={
          <>
            Meet Dr. Malek <span className="italic text-bronze">Gaashan</span>
          </>
        }
        lead={`${profile.position} — ${profile.postnominals}`}
      />

      {/* BIOGRAPHY */}
      <section className="py-28 md:py-36">
        <div className="container-x grid gap-16 lg:grid-cols-12">
          <Reveal className="lg:col-span-5">
            <div className="lg:sticky lg:top-32">
              <Photo src={profile.portrait} alt={`Portrait of ${profile.name}`} label={`Approved portrait of ${profile.name}`} position="center 20%" className="aspect-[4/5] rounded-t-full" sizes="(min-width: 1024px) 38vw, 100vw" />
              <p className="mt-6 font-serif text-xl italic leading-relaxed text-midnight/80">“{profile.philosophy}”</p>
            </div>
          </Reveal>
          <div className="lg:col-span-6 lg:col-start-7">
            <Reveal>
              <Eyebrow index="I">Biography</Eyebrow>
            </Reveal>
            <Reveal delay={0.1}>
              <p className="mt-8 font-serif text-3xl leading-snug text-midnight md:text-[2.1rem]">{profile.bioBody[0]}</p>
              <div className="prose-editorial mt-8 text-lg leading-relaxed text-charcoal/75">
                {profile.bioBody.slice(1).map((p) => (
                  <p key={p}>{p}</p>
                ))}
              </div>
            </Reveal>
            <Reveal delay={0.15}>
              <div className="mt-16 border-t border-midnight/15 pt-10">
                <p className="eyebrow text-bronze">Clinical interests</p>
                <ul className="mt-6">
                  {profile.interests.map((it, i) => (
                    <li key={it} className="flex items-baseline gap-6 border-b border-midnight/10 py-4">
                      <span className="font-serif text-sm italic text-bronze">0{i + 1}</span>
                      <span className="font-serif text-2xl text-midnight">{it}</span>
                    </li>
                  ))}
                </ul>
              </div>
            </Reveal>
          </div>
        </div>
      </section>

      {/* TIMELINE */}
      <section className="bg-sand py-28 md:py-36">
        <div className="container-x">
          <div className="mb-20 grid gap-8 md:grid-cols-4 md:gap-10">
            <div className="md:col-span-3 md:col-start-2 md:pl-12">
              <Reveal>
                <Eyebrow index="II">Professional Journey</Eyebrow>
              </Reveal>
              <Reveal delay={0.1}>
                <h2 className="mt-6 max-w-2xl text-4xl leading-tight text-midnight md:text-6xl">
                  Three health systems. <span className="italic">One surgical focus.</span>
                </h2>
              </Reveal>
            </div>
          </div>
          <Timeline items={timeline} />
        </div>
      </section>

      {/* EDUCATION */}
      <section className="py-28 md:py-36">
        <div className="container-x grid gap-16 lg:grid-cols-12">
          <div className="lg:col-span-4">
            <Reveal>
              <Eyebrow index="III">Qualifications</Eyebrow>
              <h2 className="mt-6 text-4xl leading-tight text-midnight md:text-5xl">
                Education & Professional <span className="italic">Qualifications</span>
              </h2>
            </Reveal>
          </div>
          <div className="lg:col-span-7 lg:col-start-6">
            <ul className="border-t border-midnight/15">
              {education.map((e, i) => (
                <Reveal as="li" key={e.title} delay={i * 0.05} className="grid gap-2 border-b border-midnight/10 py-7 sm:grid-cols-[1fr_auto] sm:items-baseline sm:gap-8">
                    <div>
                      <p className="font-serif text-3xl text-midnight">{e.title}</p>
                      <p className="mt-1 text-sm text-charcoal/65">{e.institution}</p>
                    </div>
                    <p className="text-sm font-semibold uppercase tracking-[0.16em] text-bronze">
                      {e.year || <Placeholder>year to confirm</Placeholder>}
                    </p>
                </Reveal>
              ))}
            </ul>

            <Reveal>
              <div className="mt-16">
                <p className="eyebrow text-bronze">Professional registration</p>
                <ul className="mt-6 grid gap-4 sm:grid-cols-2">
                  {registrations.map((r) => (
                    <li key={r.body} className="bg-white p-6">
                      <p className="font-serif text-xl text-midnight">{r.body}</p>
                      <p className="mt-2 text-sm text-charcoal/65">
                        {r.detail || <Placeholder>registration details to be added</Placeholder>}
                      </p>
                    </li>
                  ))}
                </ul>
              </div>
            </Reveal>
          </div>
        </div>
      </section>

      {/* AWARDS */}
      <section className="bg-sand py-28 md:py-36">
        <div className="container-x relative">
          <Reveal>
            <Eyebrow index="IV">
              Awards & Distinctions
            </Eyebrow>
          </Reveal>
          <div className="mt-14 grid gap-6 md:grid-cols-3">
            {awards.map((a, i) => (
              <Reveal key={a.title} delay={i * 0.1} className="bg-white transition-shadow duration-700 hover:shadow-[0_30px_60px_-30px_rgba(16,29,44,0.3)]">
                <div className="flex h-full flex-col justify-between p-8 md:p-10">
                  <p className="font-serif text-6xl text-bronze">{a.year || "—"}</p>
                  <div className="mt-16">
                    <p className="font-serif text-2xl leading-snug text-midnight">{a.title}</p>
                    <p className="mt-3 text-sm text-charcoal/60">{a.organisation}</p>
                  </div>
                </div>
              </Reveal>
            ))}
          </div>
          <div className="mt-16">
            <ButtonLink href="/expertise" variant="dark">Explore Surgical Expertise</ButtonLink>
          </div>
        </div>
      </section>
    </>
  );
}
