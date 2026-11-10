import Image from "next/image";
import Link from "next/link";
import { ArrowUpRight } from "lucide-react";
import { Reveal } from "./motion";

/** Approved photograph, or a refined labelled placeholder when none has been supplied. */
export function Photo({
  src,
  alt,
  label,
  className = "",
  sizes = "(min-width: 1024px) 50vw, 100vw",
  priority,
  position = "center",
  zoom = 1,
}: {
  src?: string;
  alt: string;
  label: string;
  className?: string;
  sizes?: string;
  priority?: boolean;
  position?: string;
  zoom?: number;
}) {
  return (
    <div className={`group/photo relative overflow-hidden bg-sand ${className}`}>
      {src ? (
        <div className="absolute inset-0" style={{ transform: `scale(${zoom || 1})`, transformOrigin: position }}>
          <Image
            src={src}
            alt={alt}
            fill
            sizes={sizes}
            priority={priority}
            style={{ objectPosition: position }}
            className="object-cover transition-transform duration-[1.6s] ease-editorial group-hover/photo:scale-[1.04]"
          />
        </div>
      ) : (
        <div
          role="img"
          aria-label={`${alt} (image placeholder)`}
          className="texture-lines absolute inset-0 flex flex-col justify-between bg-sand p-5 text-midnight/50"
        >
          <span className="flex items-center gap-3">
            <span className="h-px w-6 bg-bronze/60" />
            <span className="eyebrow text-[0.6rem] text-bronze">Photograph</span>
          </span>
          <span className="max-w-[24ch]">
            <span className="block font-serif text-xl italic leading-snug text-midnight/70">{label}</span>
            <span className="mt-2 block text-[0.62rem] uppercase tracking-[0.2em] text-midnight/40">
              Approved image to be supplied
            </span>
          </span>
        </div>
      )}
    </div>
  );
}

export function Eyebrow({ index, children, light }: { index?: string; children: React.ReactNode; light?: boolean }) {
  return (
    <p className={`eyebrow flex items-center gap-4 ${light ? "text-champagne-light" : "text-bronze"}`}>
      {index && <span className="font-serif text-sm tracking-normal opacity-80">{index}</span>}
      <span className="h-px w-10 bg-current opacity-50" />
      <span>{children}</span>
    </p>
  );
}

/** Light editorial page header with an optional photograph. */
export function PageHero({
  index,
  eyebrow,
  title,
  lead,
  image,
  imageAlt = "",
  imagePosition,
}: {
  index: string;
  eyebrow: string;
  title: React.ReactNode;
  lead?: string;
  image?: string;
  imageAlt?: string;
  imagePosition?: string;
}) {
  return (
    <section className="relative overflow-hidden bg-paper pb-16 pt-36 md:pb-24 md:pt-44">
      <span
        aria-hidden
        className="pointer-events-none absolute -left-6 top-20 select-none font-serif text-[14rem] leading-none text-midnight/[0.035] md:text-[24rem]"
      >
        {index}
      </span>
      <div className={`container-x relative grid items-end gap-12 ${image ? "lg:grid-cols-12" : ""}`}>
        <div className={image ? "lg:col-span-7" : ""}>
          <Reveal>
            <Eyebrow index={index}>{eyebrow}</Eyebrow>
          </Reveal>
          <Reveal delay={0.1}>
            <h1 className="mt-8 max-w-5xl text-5xl leading-[1.02] text-midnight sm:text-6xl md:text-7xl lg:text-[5.2rem]">
              {title}
            </h1>
          </Reveal>
          {lead && (
            <Reveal delay={0.2}>
              <p className="mt-8 max-w-2xl text-lg leading-relaxed text-charcoal/70">{lead}</p>
            </Reveal>
          )}
        </div>
        {image && (
          <Reveal delay={0.2} className="lg:col-span-5">
            <div className="relative">
              <span aria-hidden className="absolute -bottom-4 -right-4 left-6 top-6 bg-sand" />
              <Photo src={image} alt={imageAlt} label={imageAlt} position={imagePosition} priority className="relative aspect-[4/3]" sizes="(min-width: 1024px) 40vw, 100vw" />
            </div>
          </Reveal>
        )}
      </div>
      <div className="container-x relative mt-16 md:mt-20">
        <div className="h-px bg-midnight/10" />
      </div>
    </section>
  );
}

export function ButtonLink({
  href,
  children,
  variant = "solid",
}: {
  href: string;
  children: React.ReactNode;
  variant?: "solid" | "ghost" | "dark" | "outline";
}) {
  const styles = {
    solid: "bg-champagne text-midnight hover:bg-midnight hover:text-ivory",
    ghost: "border border-ivory/25 text-ivory hover:border-champagne hover:text-champagne-light",
    dark: "bg-midnight text-ivory hover:bg-champagne hover:text-midnight",
    outline: "border border-midnight/25 text-midnight hover:border-midnight hover:bg-midnight hover:text-ivory",
  }[variant];
  return (
    <Link
      href={href}
      className={`group inline-flex items-center gap-3 px-7 py-4 text-[0.72rem] font-semibold uppercase tracking-[0.2em] transition-colors duration-500 ${styles}`}
    >
      {children}
      <ArrowUpRight
        aria-hidden
        className="size-4 transition-transform duration-500 ease-editorial group-hover:-translate-y-0.5 group-hover:translate-x-0.5"
        strokeWidth={1.5}
      />
    </Link>
  );
}

/** Shown wherever a fact has not yet been verified/supplied, so nothing is invented. */
export function Placeholder({ children }: { children: React.ReactNode }) {
  return <span className="italic text-charcoal/50">[{children}]</span>;
}

export function Disclaimer({ className = "" }: { className?: string }) {
  return (
    <aside className={`border-l-2 border-champagne bg-white px-6 py-5 text-sm leading-relaxed text-charcoal/75 ${className}`}>
      <p className="eyebrow mb-2 text-bronze">Important</p>
      <p>
        The information on this website is general and educational. It is not a substitute for individual medical
        advice, diagnosis or treatment, and no specific outcome can be guaranteed. Please discuss your own situation
        with your GP or treating team. This website does not provide emergency services — in an emergency, call 999 in
        the UK or your local emergency number.
      </p>
    </aside>
  );
}

/** Typographic stand-in when no conference photograph has been supplied. */
export function ConferenceCard({ event, location, type }: { event: string; location: string; type: string }) {
  return (
    <div className="relative flex aspect-[5/4] flex-col justify-between overflow-hidden bg-white p-10 shadow-[0_30px_60px_-40px_rgba(16,29,44,0.4)]">
      <span aria-hidden className="absolute -right-24 -top-24 size-80 rounded-full border border-champagne/40" />
      <span aria-hidden className="absolute -right-10 -top-10 size-52 rounded-full border border-champagne/30" />
      <p className="eyebrow text-bronze">{type}</p>
      <div>
        <p className="font-serif text-7xl leading-none text-midnight md:text-8xl">
          {event.replace(/\s*\d{4}$/, "")} <span className="italic text-bronze">{event.match(/\d{4}$/)?.[0]}</span>
        </p>
        <p className="mt-4 text-sm uppercase tracking-[0.24em] text-charcoal/60">{location}</p>
      </div>
    </div>
  );
}
