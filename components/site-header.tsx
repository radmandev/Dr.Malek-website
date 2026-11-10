"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useEffect, useState } from "react";
import { AnimatePresence, motion, useScroll, useSpring } from "framer-motion";
import { ChevronDown, Menu, X } from "lucide-react";

const NAV = [
  { href: "/", label: "Home" },
  { href: "/about", label: "About" },
  { href: "/expertise", label: "Expertise", dropdown: true },
  { href: "/experience", label: "Experience" },
  { href: "/research", label: "Research" },
  { href: "/conferences", label: "Conferences" },
  { href: "/patient-information", label: "Patient Information" },
  { href: "/contact", label: "Contact" },
];

export function SiteHeader({ expertise }: { expertise: { slug: string; title: string }[] }) {
  const pathname = usePathname();
  const [scrolled, setScrolled] = useState(false);
  const [open, setOpen] = useState(false);
  const { scrollYProgress } = useScroll();
  const progress = useSpring(scrollYProgress, { stiffness: 120, damping: 30, restDelta: 0.001 });

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 24);
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);

  useEffect(() => setOpen(false), [pathname]);
  useEffect(() => {
    document.body.style.overflow = open ? "hidden" : "";
    const onKey = (e: KeyboardEvent) => e.key === "Escape" && setOpen(false);
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, [open]);

  const isActive = (href: string) => (href === "/" ? pathname === "/" : pathname.startsWith(href));

  return (
    <header
      className={`fixed inset-x-0 top-0 z-50 text-midnight transition-[background-color,padding,border-color,box-shadow] duration-700 ease-editorial ${
        scrolled ? "border-b border-midnight/10 bg-paper/90 py-3 shadow-[0_8px_30px_-12px_rgba(16,29,44,0.15)] backdrop-blur-md" : "border-b border-transparent py-6"
      }`}
    >
      <a
        href="#main"
        className="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:bg-champagne focus:px-4 focus:py-2 focus:text-midnight"
      >
        Skip to content
      </a>
      <div className="container-x flex items-center justify-between gap-8">
        <Link href="/" className="group flex flex-col leading-none" aria-label="Dr. Malek Gaashan — home">
          <span className="font-serif text-[1.6rem] tracking-tight">
            Dr. Malek <span className="italic text-bronze">Gaashan</span>
          </span>
          <span className="mt-1.5 text-[0.56rem] font-semibold uppercase tracking-[0.32em] text-charcoal/55">
            Consultant Urological Surgeon
          </span>
        </Link>

        <nav aria-label="Primary" className="hidden xl:block">
          <ul className="flex items-center gap-7">
            {NAV.slice(1).map((item) => (
              <li key={item.href} className="group/nav relative">
                <Link
                  href={item.href}
                  aria-current={isActive(item.href) ? "page" : undefined}
                  className={`relative flex items-center gap-1 py-2 text-[0.7rem] font-semibold uppercase tracking-[0.18em] transition-colors duration-300 hover:text-bronze ${
                    isActive(item.href) ? "text-midnight" : "text-charcoal/70"
                  }`}
                >
                  {item.label}
                  {item.dropdown && (
                    <ChevronDown aria-hidden className="size-3 transition-transform group-hover/nav:rotate-180" />
                  )}
                  <span
                    className={`absolute -bottom-0.5 left-0 h-px bg-champagne transition-all duration-500 ease-editorial ${
                      isActive(item.href) ? "w-full" : "w-0 group-hover/nav:w-full"
                    }`}
                  />
                </Link>
                {item.dropdown && (
                  <div className="invisible absolute left-1/2 top-full w-80 -translate-x-1/2 pt-5 opacity-0 transition-all duration-500 ease-editorial group-focus-within/nav:visible group-focus-within/nav:opacity-100 group-hover/nav:visible group-hover/nav:opacity-100">
                    <ul className="border border-midnight/10 bg-paper p-2 shadow-[0_24px_60px_-20px_rgba(16,29,44,0.25)]">
                      {expertise.map((e, i) => (
                        <li key={e.slug}>
                          <Link
                            href={`/expertise#${e.slug}`}
                            className="flex items-baseline gap-4 px-4 py-3 text-charcoal transition-colors hover:bg-sand hover:text-midnight"
                          >
                            <span className="font-serif text-sm italic text-bronze">0{i + 1}</span>
                            <span className="font-serif text-lg">{e.title}</span>
                          </Link>
                        </li>
                      ))}
                    </ul>
                  </div>
                )}
              </li>
            ))}
          </ul>
        </nav>

        <button
          type="button"
          onClick={() => setOpen(true)}
          className="flex items-center gap-3 text-[0.68rem] font-semibold uppercase tracking-[0.22em] xl:hidden"
          aria-expanded={open}
          aria-controls="mobile-menu"
        >
          Menu <Menu aria-hidden className="size-5" strokeWidth={1.25} />
        </button>
      </div>

      <motion.div
        aria-hidden
        className="absolute bottom-0 left-0 h-px w-full origin-left bg-champagne"
        style={{ scaleX: progress }}
      />

      <AnimatePresence>
        {open && (
          <motion.div
            id="mobile-menu"
            role="dialog"
            aria-modal="true"
            aria-label="Site menu"
            className="fixed inset-0 z-50 flex flex-col overflow-y-auto bg-paper text-midnight"
            initial={{ clipPath: "inset(0 0 100% 0)" }}
            animate={{ clipPath: "inset(0 0 0% 0)" }}
            exit={{ clipPath: "inset(0 0 100% 0)" }}
            transition={{ duration: 0.7, ease: [0.22, 1, 0.36, 1] }}
          >
            <div className="container-x flex items-center justify-between py-6">
              <span className="font-serif text-[1.6rem]">
                Dr. Malek <span className="italic text-bronze">Gaashan</span>
              </span>
              <button
                type="button"
                onClick={() => setOpen(false)}
                className="flex items-center gap-3 text-[0.68rem] font-semibold uppercase tracking-[0.22em]"
                autoFocus
              >
                Close <X aria-hidden className="size-5" strokeWidth={1.25} />
              </button>
            </div>
            <nav aria-label="Mobile" className="container-x flex-1 py-8">
              <ul>
                {NAV.map((item, i) => (
                  <motion.li
                    key={item.href}
                    initial={{ opacity: 0, y: 24 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.25 + i * 0.05, duration: 0.7, ease: [0.22, 1, 0.36, 1] }}
                    className="border-b border-midnight/10"
                  >
                    <Link
                      href={item.href}
                      className={`flex items-baseline gap-5 py-4 font-serif text-4xl sm:text-5xl ${
                        isActive(item.href) ? "text-bronze" : "text-midnight"
                      }`}
                    >
                      <span className="w-8 font-sans text-xs tracking-widest text-charcoal/40">0{i + 1}</span>
                      {item.label}
                    </Link>
                  </motion.li>
                ))}
              </ul>
            </nav>
            <p className="container-x pb-8 text-[0.65rem] uppercase tracking-[0.24em] text-charcoal/50">
              MD · MRCS · FRCS (Urol)
            </p>
          </motion.div>
        )}
      </AnimatePresence>
    </header>
  );
}
