"use client";

import { useRef, useState } from "react";
import { motion, useScroll, useSpring } from "framer-motion";
import type { SiteContent } from "@/lib/content";

/** Vertical career timeline: a champagne line draws as you scroll; entries expand on click/focus. */
export function Timeline({ items }: { items: SiteContent["timeline"] }) {
  const ref = useRef<HTMLOListElement>(null);
  const { scrollYProgress } = useScroll({ target: ref, offset: ["start 75%", "end 60%"] });
  const scaleY = useSpring(scrollYProgress, { stiffness: 90, damping: 30 });
  const [active, setActive] = useState(0);

  return (
    <ol ref={ref} className="relative">
      <span aria-hidden className="absolute bottom-0 left-[7px] top-2 w-px bg-midnight/10 md:left-[calc(25%+7px)]" />
      <motion.span
        aria-hidden
        style={{ scaleY }}
        className="absolute bottom-0 left-[7px] top-2 w-px origin-top bg-champagne md:left-[calc(25%+7px)]"
      />
      {items.map((item, i) => {
        const open = active === i;
        const start = item.roles[item.roles.length - 1]?.period.match(/\d{4}/)?.[0];
        const end = item.roles[0]?.period.match(/(\d{4}|Present)\s*$/)?.[0];
        return (
          <li key={item.organisation + i} className="relative grid gap-2 pb-14 pl-10 md:grid-cols-4 md:gap-10 md:pl-0">
            <p className="font-serif text-2xl text-midnight/50 md:pr-12 md:text-right md:text-3xl">
              {start}
              {end && end !== start && <span className="text-bronze"> — {end}</span>}
            </p>
            <span
              aria-hidden
              className={`absolute left-0 top-2.5 size-[15px] rounded-full border transition-colors duration-500 md:left-[25%] ${
                open ? "border-champagne bg-champagne" : "border-midnight/30 bg-sand"
              }`}
            />
            <div className="md:col-span-3 md:pl-12">
              <button
                type="button"
                onClick={() => setActive(open ? -1 : i)}
                onFocus={() => setActive(i)}
                aria-expanded={open}
                className="group text-left"
              >
                <span className="eyebrow block text-bronze">{item.location}</span>
                <span className="mt-2 block font-serif text-3xl leading-tight text-midnight transition-colors group-hover:text-bronze md:text-4xl">
                  {item.organisation}
                </span>
              </button>
              <motion.div
                initial={false}
                animate={{ height: open ? "auto" : 0, opacity: open ? 1 : 0 }}
                transition={{ duration: 0.6, ease: [0.22, 1, 0.36, 1] }}
                className="overflow-hidden"
              >
                <ul className="mt-6 space-y-4 border-l border-midnight/10 pl-6">
                  {item.roles.map((r) => (
                    <li key={r.title}>
                      <p className="text-lg font-medium text-midnight">{r.title}</p>
                      <p className="mt-1 text-sm text-charcoal/60">{r.period}</p>
                    </li>
                  ))}
                </ul>
              </motion.div>
              {!open && (
                <p className="mt-3 text-sm text-charcoal/60">{item.roles.map((r) => r.title).join(" · ")}</p>
              )}
            </div>
          </li>
        );
      })}
    </ol>
  );
}
