"use client";

import { useState } from "react";
import { ArrowUpRight, Check } from "lucide-react";

type Status = { kind: "idle" | "sending" | "sent" } | { kind: "error"; message: string };

const field =
  "peer w-full border-0 border-b border-midnight/20 bg-transparent px-0 pb-3 pt-6 text-lg text-midnight placeholder-transparent transition-colors focus:border-champagne focus:outline-none focus:ring-0";
const label =
  "pointer-events-none absolute left-0 top-0 text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-charcoal/55";

export function ContactForm() {
  const [status, setStatus] = useState<Status>({ kind: "idle" });

  async function onSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    const form = e.currentTarget;
    setStatus({ kind: "sending" });
    try {
      const res = await fetch("/api/contact", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(Object.fromEntries(new FormData(form))),
      });
      const json = await res.json().catch(() => ({}));
      if (!res.ok) throw new Error(json.error ?? "Something went wrong. Please try again.");
      form.reset();
      setStatus({ kind: "sent" });
    } catch (err) {
      setStatus({ kind: "error", message: (err as Error).message });
    }
  }

  if (status.kind === "sent") {
    return (
      <div role="status" className="mt-10 border-t border-champagne pt-10">
        <Check aria-hidden className="size-8 text-bronze" strokeWidth={1.25} />
        <p className="mt-6 font-serif text-4xl text-midnight">Thank you.</p>
        <p className="mt-3 max-w-md text-charcoal/70">Your enquiry has been received and will be reviewed in due course.</p>
      </div>
    );
  }

  return (
    <form onSubmit={onSubmit} className="relative mt-10 grid gap-10 sm:grid-cols-2">
      {/* Honeypot — hidden from people and assistive tech */}
      <div aria-hidden className="absolute -left-[9999px]">
        <label>
          Company <input name="company" tabIndex={-1} autoComplete="off" />
        </label>
      </div>
      <div className="relative">
        <input id="name" name="name" required maxLength={120} autoComplete="name" placeholder="Name" className={field} />
        <label htmlFor="name" className={label}>Name</label>
      </div>
      <div className="relative">
        <input id="email" name="email" type="email" required maxLength={200} autoComplete="email" placeholder="Email" className={field} />
        <label htmlFor="email" className={label}>Email</label>
      </div>
      <div className="relative sm:col-span-2">
        <input id="subject" name="subject" required maxLength={200} placeholder="Subject" className={field} />
        <label htmlFor="subject" className={label}>Subject</label>
      </div>
      <div className="relative sm:col-span-2">
        <textarea id="message" name="message" required maxLength={5000} rows={6} placeholder="Message" className={`${field} resize-y`} />
        <label htmlFor="message" className={label}>Message</label>
      </div>
      <div className="flex flex-col gap-6 sm:col-span-2 sm:flex-row sm:items-center sm:justify-between">
        <p className="max-w-sm text-xs leading-relaxed text-charcoal/55">
          Please do not include confidential medical information. This form is for professional enquiries only.
        </p>
        <button
          type="submit"
          disabled={status.kind === "sending"}
          className="group inline-flex items-center gap-3 bg-midnight px-8 py-4 text-[0.72rem] font-semibold uppercase tracking-[0.2em] text-ivory transition-colors hover:bg-champagne hover:text-midnight disabled:opacity-60"
        >
          {status.kind === "sending" ? "Sending…" : "Send enquiry"}
          <ArrowUpRight aria-hidden className="size-4 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
        </button>
      </div>
      {status.kind === "error" && (
        <p role="alert" className="border-l border-red-700 pl-4 text-sm text-red-800 sm:col-span-2">
          {status.message}
        </p>
      )}
    </form>
  );
}
