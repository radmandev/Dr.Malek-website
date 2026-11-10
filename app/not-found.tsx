import Link from "next/link";

export default function NotFound() {
  return (
    <main className="flex min-h-screen flex-col items-center justify-center bg-midnight px-6 text-center text-ivory">
      <p className="eyebrow text-champagne">404</p>
      <h1 className="mt-6 text-5xl md:text-7xl">
        Page <span className="italic text-champagne-light">not found</span>
      </h1>
      <Link href="/" className="link-underline mt-10 text-xs uppercase tracking-[0.2em] text-ivory/70">
        Return home
      </Link>
    </main>
  );
}
