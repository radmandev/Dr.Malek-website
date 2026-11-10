import { NextResponse } from "next/server";

const LIMITS = { name: 120, email: 200, subject: 200, message: 5000 } as const;
const EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

export async function POST(req: Request) {
  let body: Record<string, unknown>;
  try {
    body = await req.json();
  } catch {
    return NextResponse.json({ error: "Invalid request." }, { status: 400 });
  }

  // Honeypot: bots fill hidden fields. Pretend success.
  if (body.company) return NextResponse.json({ ok: true });

  const data = {} as Record<keyof typeof LIMITS, string>;
  for (const [k, max] of Object.entries(LIMITS) as [keyof typeof LIMITS, number][]) {
    const v = typeof body[k] === "string" ? (body[k] as string).trim() : "";
    if (!v) return NextResponse.json({ error: `Please provide your ${k}.` }, { status: 400 });
    if (v.length > max) return NextResponse.json({ error: `Your ${k} is too long.` }, { status: 400 });
    data[k] = v;
  }
  if (!EMAIL.test(data.email)) return NextResponse.json({ error: "Please provide a valid email address." }, { status: 400 });

  const url = process.env.NEXT_PUBLIC_SUPABASE_URL;
  const key = process.env.SUPABASE_SERVICE_ROLE_KEY;
  if (!url || !key) {
    return NextResponse.json(
      { error: "The enquiry service is not yet connected. Please use the institutional contact details instead." },
      { status: 503 },
    );
  }

  // ponytail: no rate limiting beyond the honeypot; add Vercel/Upstash rate limiting if spam appears.
  const res = await fetch(`${url}/rest/v1/enquiries`, {
    method: "POST",
    headers: { apikey: key, Authorization: `Bearer ${key}`, "Content-Type": "application/json", Prefer: "return=minimal" },
    body: JSON.stringify(data),
  });
  if (!res.ok) {
    console.error("Enquiry insert failed", res.status, await res.text());
    return NextResponse.json({ error: "Sorry, your enquiry could not be sent. Please try again later." }, { status: 502 });
  }
  return NextResponse.json({ ok: true });
}
