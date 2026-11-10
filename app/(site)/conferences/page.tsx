import type { Metadata } from "next";
import { getContent } from "@/lib/get-content";
import { ConferenceCard, Eyebrow, PageHero, Photo } from "@/components/ui";
import { Reveal } from "@/components/motion";

export const metadata: Metadata = {
  title: "Conferences & Education",
  description:
    "Speaking engagements and surgical education by Dr. Malek Gaashan, including an oral presentation on robotic cystectomy and intracorporeal reconstruction at AAU 2026, Jeddah.",
  alternates: { canonical: "/conferences" },
};

export default async function ConferencesPage() {
  const { conferences } = await getContent();
  const featured = conferences.find((c) => c.featured);
  const others = conferences.filter((c) => c !== featured);

  return (
    <>
      <PageHero
        index="06"
        eyebrow="Conferences & Education"
        title={
          <>
            Sharing technique. <span className="italic text-bronze">Shaping the learning curve.</span>
          </>
        }
        lead="Professional speaking engagements, surgical education and international academic meetings."
      />

      {featured && (
        <section className="bg-ivory py-24 md:py-32">
          <div className="container-x">
            <Reveal>
              <Eyebrow index="I">Featured Presentation</Eyebrow>
            </Reveal>
            <div className="mt-12 grid bg-white shadow-[0_40px_80px_-50px_rgba(16,29,44,0.45)] lg:grid-cols-12">
              <Reveal className="lg:col-span-5">
                {featured.image ? (
                  <Photo
                    src={featured.image}
                    alt={`${featured.event}, ${featured.location}`}
                    label="Medical conference & academic presentation"
                    className="h-full min-h-80"
                    sizes="(min-width: 1024px) 40vw, 100vw"
                  />
                ) : (
                  <div className="h-full bg-sand p-6">
                    <ConferenceCard event={featured.event} location={featured.location} type={featured.type} />
                  </div>
                )}
              </Reveal>
              <div className="relative p-8 md:p-14 lg:col-span-7">
                <Reveal>
                  <p className="eyebrow text-bronze">
                    {featured.event} · {featured.location}
                  </p>
                  <h2 className="mt-8 text-3xl leading-snug text-midnight md:text-[2.4rem]">“{featured.title}”</h2>
                  {featured.summary && <p className="mt-6 leading-relaxed text-charcoal/70">{featured.summary}</p>}
                </Reveal>
                <Reveal delay={0.1}>
                  <dl className="mt-12 grid gap-4 sm:grid-cols-3">
                    {[
                      ["Format", featured.type],
                      ["Date", featured.date],
                      ["Theme", featured.theme],
                    ].map(([k, v]) => (
                      <div key={k} className="bg-paper p-5">
                        <dt className="eyebrow text-bronze">{k}</dt>
                        <dd className="mt-2 text-sm leading-relaxed text-charcoal/80">{v}</dd>
                      </div>
                    ))}
                  </dl>
                </Reveal>
              </div>
            </div>
          </div>
        </section>
      )}

      <section className="bg-sand py-28 md:py-36">
        <div className="container-x grid gap-12 md:grid-cols-12">
          <Reveal className="md:col-span-5">
            <Eyebrow index="II">Engagements</Eyebrow>
            <h2 className="mt-6 text-4xl leading-tight text-midnight md:text-5xl">
              Conferences, workshops & <span className="italic">speaking</span>
            </h2>
          </Reveal>
          <div className="md:col-span-6 md:col-start-7">
            {others.length ? (
              <ul className="border-t border-midnight/15">
                {others.map((c) => (
                  <li key={c.event + c.title} className="grid gap-2 border-b border-midnight/10 py-7 sm:grid-cols-[8rem_1fr] sm:gap-8">
                    <p className="font-serif text-2xl text-bronze">{c.date}</p>
                    <div>
                      <p className="font-serif text-2xl text-midnight">{c.title}</p>
                      <p className="mt-2 text-sm text-charcoal/60">
                        {[c.event, c.location, c.type].filter(Boolean).join(" · ")}
                      </p>
                    </div>
                  </li>
                ))}
              </ul>
            ) : (
              <p className="leading-relaxed text-charcoal/70">
                Future conferences, workshops and speaking engagements will be published here as they are confirmed.
              </p>
            )}
          </div>
        </div>
      </section>
    </>
  );
}
