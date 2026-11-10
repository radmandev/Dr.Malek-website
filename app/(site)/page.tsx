import Image from "next/image";
import Link from "next/link";
import { ArrowDown, ArrowUpRight } from "lucide-react";
import { getContent } from "@/lib/get-content";
import { ButtonLink, ConferenceCard, Eyebrow, Photo } from "@/components/ui";
import { Counter, Reveal } from "@/components/motion";

const ACHIEVEMENTS = [
  ["Completion of 50+ robotic cystectomies", "A published career milestone in robotic bladder cancer surgery."],
  ["Advanced robotic surgical practice", "Consultant pelvic robotic surgeon at University Hospitals of Leicester."],
  ["Complex reconstructive procedures", "Intracorporeal ileal conduit and orthotopic neobladder reconstruction."],
  ["International academic participation", "Invited oral presentation at AAU 2026, Jeddah."],
];

export default async function HomePage() {
  const { profile, credentials, expertise, stats, conferences, achievements } = await getContent();
  const featured = expertise.slice(0, 4);
  const conference = conferences.find((c) => c.featured) ?? conferences[0];
  const teamPhoto = achievements.cases.find((c) => c.image.includes("team")) ?? achievements.cases[0];
  const [headA, headB] = splitHeadline(profile.heroHeadline);

  return (
    <>
      {/* HERO */}
      <section className="relative overflow-hidden bg-paper pb-24 pt-32 md:pt-40 lg:min-h-[min(100svh,980px)] lg:pb-16">
        <OrbitMotif />
        <div className="container-x relative grid items-center gap-16 lg:grid-cols-12 lg:gap-10">
          <div className="order-2 lg:order-1 lg:col-span-7">
            <Reveal>
              <Eyebrow>Consultant Urological Surgeon · United Kingdom</Eyebrow>
            </Reveal>
            <Reveal delay={0.1}>
              <h1 className="mt-8 text-[2.9rem] leading-[0.98] text-midnight sm:text-6xl md:text-7xl xl:text-[5.4rem]">
                {headA} <span className="italic text-bronze">{headB}</span>
              </h1>
            </Reveal>
            <Reveal delay={0.2}>
              <p className="mt-8 max-w-xl text-lg leading-relaxed text-charcoal/70">{profile.heroText}</p>
            </Reveal>
            <Reveal delay={0.3}>
              <div className="mt-10 max-w-xl border-l-2 border-champagne pl-6">
                <p className="font-serif text-3xl text-midnight">{profile.name}</p>
                <p className="mt-2 text-[0.68rem] font-semibold uppercase tracking-[0.26em] text-bronze">{profile.postnominals}</p>
                <p className="mt-3 text-sm leading-relaxed text-charcoal/65">
                  {profile.position}
                  <br />
                  {profile.focus}
                </p>
              </div>
            </Reveal>
            <Reveal delay={0.4}>
              <div className="mt-12 flex flex-wrap gap-4">
                <ButtonLink href="/expertise" variant="dark">
                  Explore Surgical Expertise
                </ButtonLink>
                <ButtonLink href="/contact" variant="outline">
                  Professional Enquiries
                </ButtonLink>
              </div>
            </Reveal>
          </div>

          <Reveal delay={0.15} className="order-1 lg:order-2 lg:col-span-5" y={40}>
            <figure className="relative mx-auto max-w-[26rem] lg:ml-auto">
              <span aria-hidden className="absolute -right-5 -top-5 bottom-10 left-10 rounded-t-full bg-sand" />
              <span aria-hidden className="absolute -right-5 -top-5 bottom-10 left-10 rounded-t-full border border-champagne/50" />
              <Photo
                src={profile.portrait}
                alt={`Portrait of ${profile.name}`}
                label={`Approved portrait of ${profile.name}`}
                position="center 20%"
                className="relative aspect-[4/5] rounded-t-full shadow-[0_40px_80px_-40px_rgba(16,29,44,0.45)]"
                sizes="(min-width: 1024px) 26rem, 90vw"
                priority
              />
              <div className="absolute -left-4 bottom-10 bg-white px-6 py-5 shadow-[0_20px_50px_-20px_rgba(16,29,44,0.35)] sm:-left-12">
                <p className="font-serif text-5xl leading-none text-midnight">
                  <Counter value={stats[0]?.value ?? 50} suffix={stats[0]?.suffix ?? "+"} />
                </p>
                <p className="mt-2 text-[0.6rem] font-semibold uppercase tracking-[0.22em] text-charcoal/60">{stats[0]?.label}</p>
              </div>
              <figcaption className="absolute -right-2 top-1/3 bg-midnight px-4 py-2.5 text-[0.6rem] font-semibold uppercase tracking-[0.24em] text-ivory sm:-right-8">
                FRCS (Urol)
              </figcaption>
            </figure>
          </Reveal>
        </div>
        <a
          href="#credentials"
          className="absolute bottom-6 left-1/2 hidden -translate-x-1/2 flex-col items-center gap-2 text-[0.6rem] uppercase tracking-[0.3em] text-charcoal/40 transition-colors hover:text-bronze lg:flex"
        >
          Scroll <ArrowDown aria-hidden className="size-4 animate-bounce" strokeWidth={1} />
        </a>
      </section>

      {/* CREDENTIALS STRIP */}
      <section id="credentials" aria-label="Credentials" className="overflow-hidden border-y border-midnight/10 bg-white py-6">
        <ul className="sr-only">
          {credentials.map((c) => (
            <li key={c}>{c}</li>
          ))}
        </ul>
        <div aria-hidden className="flex w-max animate-marquee">
          {[0, 1].map((k) => (
            <div key={k} className="flex shrink-0">
              {[...credentials, ...credentials].map((c, i) => (
                <span key={i} className="flex items-center whitespace-nowrap font-serif text-2xl italic text-midnight/80 md:text-3xl">
                  <span className="px-10">{c}</span>
                  <span className="text-xs not-italic text-champagne">◆</span>
                </span>
              ))}
            </div>
          ))}
        </div>
      </section>

      {/* INTRODUCTION */}
      <section className="bg-ivory py-28 md:py-36">
        <div className="container-x grid gap-14 lg:grid-cols-12">
          <div className="lg:col-span-5">
            <Reveal>
              <Eyebrow index="01">Philosophy</Eyebrow>
            </Reveal>
            <Reveal delay={0.1}>
              <h2 className="mt-8 text-5xl leading-[1.02] text-midnight md:text-6xl">
                Precision in Surgery.
                <br />
                <span className="italic text-bronze">Excellence in Care.</span>
              </h2>
            </Reveal>
            <Reveal delay={0.2}>
              <Photo
                src={expertise[0]?.image}
                alt="Robotic operating theatre"
                label="Robotic operating theatre"
                className="mt-12 aspect-[4/3]"
                sizes="(min-width: 1024px) 38vw, 100vw"
              />
            </Reveal>
          </div>
          <div className="lg:col-span-6 lg:col-start-7 lg:pt-20">
            <Reveal>
              <p className="font-serif text-2xl leading-snug text-midnight md:text-[1.9rem]">{profile.introBody[0]}</p>
            </Reveal>
            <Reveal delay={0.1}>
              <div className="prose-editorial mt-8 leading-relaxed text-charcoal/75">
                {profile.introBody.slice(1).map((p) => (
                  <p key={p}>{p}</p>
                ))}
              </div>
            </Reveal>
            <Reveal delay={0.15}>
              <blockquote className="mt-12 border-t border-midnight/10 pt-8 font-serif text-2xl italic leading-snug text-midnight/80">
                “{profile.philosophy}”
              </blockquote>
            </Reveal>
            <Reveal delay={0.2}>
              <Link href="/about" className="group mt-10 inline-flex items-center gap-3 text-[0.72rem] font-semibold uppercase tracking-[0.2em] text-midnight">
                <span className="link-underline">Meet Dr. Malek</span>
                <ArrowUpRight aria-hidden className="size-4 text-bronze transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
              </Link>
            </Reveal>
          </div>
        </div>
      </section>

      {/* FEATURED EXPERTISE */}
      <section className="bg-paper py-28 md:py-36">
        <div className="container-x">
          <div className="flex flex-col justify-between gap-8 md:flex-row md:items-end">
            <div>
              <Reveal>
                <Eyebrow index="02">Surgical Expertise</Eyebrow>
              </Reveal>
              <Reveal delay={0.1}>
                <h2 className="mt-6 max-w-2xl text-4xl leading-tight text-midnight md:text-5xl">
                  Focused on the most demanding areas of <span className="italic text-bronze">robotic urology</span>.
                </h2>
              </Reveal>
            </div>
            <Reveal delay={0.2}>
              <Link href="/expertise" className="link-underline text-[0.72rem] font-semibold uppercase tracking-[0.2em] text-midnight">
                All areas of expertise →
              </Link>
            </Reveal>
          </div>

          <div className="mt-16 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            {featured.map((e, i) => (
              <Reveal key={e.slug} delay={i * 0.08} className={i % 2 ? "lg:mt-16" : ""}>
                <Link
                  href={`/expertise#${e.slug}`}
                  className="group flex h-full flex-col bg-white shadow-[0_1px_0_rgba(16,29,44,0.06)] transition-shadow duration-700 hover:shadow-[0_30px_60px_-30px_rgba(16,29,44,0.35)]"
                >
                  <Photo src={e.image} alt={e.title} label={e.imageLabel} position={e.imagePosition} zoom={e.imageZoom} className="aspect-[4/3]" sizes="(min-width: 1024px) 25vw, (min-width: 768px) 50vw, 100vw" />
                  <div className="flex flex-1 flex-col p-7">
                    <p className="font-serif text-sm italic text-bronze">0{i + 1}</p>
                    <h3 className="mt-2 text-[1.75rem] leading-tight text-midnight">{e.title}</h3>
                    <p className="mt-3 flex-1 text-sm leading-relaxed text-charcoal/70">{e.summary}</p>
                    <span className="mt-6 inline-flex items-center gap-2 text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-midnight">
                      Discover
                      <ArrowUpRight aria-hidden className="size-4 text-bronze transition-transform duration-500 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" strokeWidth={1.5} />
                    </span>
                  </div>
                </Link>
              </Reveal>
            ))}
          </div>
        </div>
      </section>

      {/* MILESTONES */}
      <section className="bg-sand py-28 md:py-36">
        <div className="container-x grid items-center gap-16 lg:grid-cols-12">
          <Reveal className="lg:col-span-6">
            <figure className="relative">
              <Photo src={teamPhoto?.image} alt={teamPhoto?.title ?? "Surgical team"} label="Surgical team collaboration" className="aspect-[4/3]" sizes="(min-width: 1024px) 45vw, 100vw" />
              <figcaption className="mt-4 text-xs uppercase tracking-[0.2em] text-charcoal/55">{teamPhoto?.caption}</figcaption>
            </figure>
          </Reveal>
          <div className="lg:col-span-5 lg:col-start-8">
            <Reveal>
              <Eyebrow index="03">Milestones</Eyebrow>
              <p className="mt-6 font-serif text-[7rem] leading-[0.85] text-midnight md:text-[9rem]">
                <Counter value={stats[0]?.value ?? 50} suffix={stats[0]?.suffix ?? "+"} />
              </p>
              <p className="mt-6 font-serif text-3xl italic text-midnight/85">{stats[0]?.label}</p>
              <p className="mt-5 leading-relaxed text-charcoal/70">{achievements.statement}</p>
            </Reveal>
            <ul className="mt-10 border-t border-midnight/15">
              {ACHIEVEMENTS.map(([t, d], i) => (
                <Reveal as="li" key={t} delay={i * 0.06} className="grid grid-cols-[2.5rem_1fr] gap-3 border-b border-midnight/15 py-5">
                  <span className="font-serif italic text-bronze">0{i + 1}</span>
                  <div>
                    <p className="font-serif text-xl text-midnight">{t}</p>
                    <p className="mt-1 text-sm text-charcoal/60">{d}</p>
                  </div>
                </Reveal>
              ))}
            </ul>
          </div>
        </div>
        <div className="container-x mt-20 grid gap-6 sm:grid-cols-3">
          {stats.map((s) => (
            <Reveal key={s.label} className="bg-white p-8">
              <p className="font-serif text-6xl text-midnight">
                <Counter value={s.value} suffix={s.suffix} />
              </p>
              <p className="eyebrow mt-4 text-bronze">{s.label}</p>
              <p className="mt-2 text-sm text-charcoal/60">{s.note}</p>
            </Reveal>
          ))}
        </div>
      </section>

      {/* CONFERENCE */}
      {conference && (
        <section className="bg-paper py-28 md:py-36">
          <div className="container-x grid items-center gap-14 lg:grid-cols-12">
            <Reveal className="lg:col-span-6">
              {conference.image ? (
                <Photo src={conference.image} alt={`${conference.event} conference`} label="Conference" className="aspect-[5/4]" />
              ) : (
                <ConferenceCard event={conference.event} location={conference.location} type={conference.type} />
              )}
            </Reveal>
            <div className="lg:col-span-5 lg:col-start-8">
              <Reveal>
                <Eyebrow index="04">Academic Contribution</Eyebrow>
              </Reveal>
              <Reveal delay={0.1}>
                <h2 className="mt-8 text-3xl leading-tight text-midnight md:text-[2.5rem]">“{conference.title}”</h2>
                <p className="mt-6 text-sm text-charcoal/65">Theme — {conference.theme}</p>
              </Reveal>
              <Reveal delay={0.2}>
                <div className="mt-10">
                  <ButtonLink href="/conferences" variant="outline">
                    Conferences & Education
                  </ButtonLink>
                </div>
              </Reveal>
            </div>
          </div>
        </section>
      )}

      {/* CLOSING */}
      <section className="border-t border-midnight/10 bg-ivory py-24 md:py-28">
        <div className="container-x flex flex-col items-start justify-between gap-10 md:flex-row md:items-center">
          <Reveal className="flex items-center gap-8">
            {profile.avatar && (
              <Image
                src={profile.avatar}
                alt=""
                width={112}
                height={112}
                className="hidden size-28 shrink-0 rounded-full object-cover ring-1 ring-champagne ring-offset-4 ring-offset-ivory sm:block"
              />
            )}
            <h2 className="max-w-2xl text-4xl leading-tight text-midnight md:text-5xl">
              Professional enquiries, collaborations & <span className="italic text-bronze">academic invitations</span>.
            </h2>
          </Reveal>
          <Reveal delay={0.1}>
            <ButtonLink href="/contact" variant="dark">
              Get in touch
            </ButtonLink>
          </Reveal>
        </div>
      </section>
    </>
  );
}

/** Italicises the final three words of the editable headline. */
function splitHeadline(h: string): [string, string] {
  const w = h.split(" ");
  return w.length > 3 ? [w.slice(0, -3).join(" "), w.slice(-3).join(" ")] : [h, ""];
}

function OrbitMotif() {
  return (
    <svg
      aria-hidden
      viewBox="0 0 800 800"
      className="pointer-events-none absolute -right-72 top-1/2 hidden w-[1100px] -translate-y-1/2 text-champagne opacity-40 lg:block"
    >
      <g className="origin-center animate-orbit" style={{ transformBox: "fill-box" }}>
        <circle cx="400" cy="400" r="390" fill="none" stroke="currentColor" strokeWidth="0.8" strokeDasharray="2 10" />
        <circle cx="790" cy="400" r="5" fill="currentColor" />
      </g>
      <circle cx="400" cy="400" r="300" fill="none" stroke="currentColor" strokeWidth="0.6" />
    </svg>
  );
}
