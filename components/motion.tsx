"use client";

import { animate, motion, useInView, useReducedMotion } from "framer-motion";
import { useEffect, useRef, useState } from "react";

const TAGS = { div: motion.div, li: motion.li, article: motion.article } as const;

export function Reveal({
  children,
  delay = 0,
  className,
  y = 28,
  as = "div",
  id,
}: {
  children: React.ReactNode;
  delay?: number;
  className?: string;
  y?: number;
  as?: keyof typeof TAGS;
  id?: string;
}) {
  const reduce = useReducedMotion();
  const Tag = TAGS[as];
  return (
    <Tag
      id={id}
      className={className}
      initial={reduce ? false : { opacity: 0, y }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true, margin: "-10% 0px" }}
      transition={{ duration: 1.1, delay, ease: [0.22, 1, 0.36, 1] }}
    >
      {children}
    </Tag>
  );
}

/** Animates from 0 to `value` once visible. Use for verified figures only. */
export function Counter({ value, suffix = "", grouping = false }: { value: number; suffix?: string; grouping?: boolean }) {
  const ref = useRef<HTMLSpanElement>(null);
  const inView = useInView(ref, { once: true });
  const reduce = useReducedMotion();
  const from = value > 1000 ? value - 40 : 0; // years count up briefly rather than from zero
  const [n, setN] = useState(reduce ? value : from);

  useEffect(() => {
    if (!inView || reduce) return;
    const c = animate(from, value, { duration: 2.2, ease: [0.22, 1, 0.36, 1], onUpdate: (v) => setN(Math.round(v)) });
    return () => c.stop();
  }, [inView, reduce, value, from]);

  return (
    <span ref={ref} aria-label={`${value}${suffix}`}>
      <span aria-hidden>
        {grouping ? n.toLocaleString("en-GB") : n}
        {suffix && <span className="text-bronze">{suffix}</span>}
      </span>
    </span>
  );
}
