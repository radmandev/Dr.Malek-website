# Dr. Malek Gaashan — Personal Medical Website

Next.js 15 (App Router) · TypeScript · Tailwind CSS v4 · Framer Motion · Lucide · Supabase (optional CMS).

## Run locally

```bash
npm install
npm run dev        # http://localhost:3000
npm run build && npm start   # production build
```

The site works with **no configuration**: it renders the bundled content in `lib/content.ts`.
Without Supabase, the contact form shows a clear "not yet connected" message (it never pretends to send), and `/admin` explains how to connect.

## Pages

| Route | Page |
|---|---|
| `/` | Home — hero, credentials, philosophy, featured expertise, milestones, conference |
| `/about` | Biography, interactive career timeline, qualifications, registration, awards |
| `/expertise` | Five specialty sections with sticky in-page navigation |
| `/experience` | 50+ robotic cystectomies, milestones, anonymised case imagery |
| `/research` | Publications + forthcoming contributions |
| `/conferences` | AAU 2026 feature + future engagements |
| `/patient-information` | Patient articles, FAQ (with FAQPage structured data), disclaimers |
| `/contact` | Enquiry form + verified practice details |
| `/admin` | Content management (Supabase) — not indexed |

SEO: per-page titles/descriptions, Open Graph image (`app/opengraph-image.tsx`), `Physician` JSON-LD, `sitemap.xml`, `robots.txt`.

## Content management (Supabase)

1. Create a Supabase project.
2. In the SQL editor, run `supabase/schema.sql` (tables, Row Level Security, `media` storage bucket).
3. **Authentication → Providers → Email:** disable "Allow new users to sign up". Then **Authentication → Users → Add user** for the administrator.
4. Authorise that user: `insert into public.admins (user_id) values ('<user-uuid>');`
5. Copy `.env.example` to `.env.local` and fill in:
   - `NEXT_PUBLIC_SUPABASE_URL`, `NEXT_PUBLIC_SUPABASE_ANON_KEY` — public, safe in the browser (RLS enforces access).
   - `SUPABASE_SERVICE_ROLE_KEY` — **server only**, used solely by `/api/contact` to store enquiries.
   - `NEXT_PUBLIC_SITE_URL` — the production domain.
6. Restart, visit `/admin`, sign in.

Every section of the site (biography, timeline, expertise, publications, conferences, achievements, patient articles, FAQs, contact details, images) is editable as a form. Saving writes one row per section to `site_content`; the public site picks up changes within ~60 seconds. Images upload to the `media` bucket.

Enquiries are stored in the `enquiries` table — read them in the Supabase Table Editor. To also receive them by email, add a Supabase Database Webhook or an email provider (e.g. Resend) call in `app/api/contact/route.ts`.

Security model: the browser only ever holds the anon key; only users listed in `admins` can write content or upload media (RLS); enquiries can only be inserted server-side and only read by admins.

## Missing assets & information to supply

Nothing below has been invented — each appears on the site as a clearly labelled placeholder until provided.

- **Professional portrait** of Dr. Malek (hero and About page). Upload via `/admin → Profile → Portrait`, or place a file in `public/images/` and set the field to `/images/<file>.jpg`.
- **Photography:** robotic operating theatre, Da Vinci system, hospital architecture, surgical team, conference — one per expertise section, the AAU feature, and three anonymised case-study images. Only approved, non-identifiable imagery.
- **Publications:** journal names, DOIs / links for both 2018 papers.
- **AAU 2026:** exact presentation date.
- **Contact:** verified institutional address, professional email, secretary number, LinkedIn URL, GMC number.
- **Registrations:** GMC details and any other registrations.
- **Qualification years** for Arab Board and Jordan Board of Urology.
- **Social links** beyond LinkedIn, if wanted.

Note: remote images must come from Supabase Storage (`*.supabase.co`) or `public/`. To allow another host, add it to `images.remotePatterns` in `next.config.ts`.

## Structure

```
app/(site)/        public pages (shared header/footer + page transition)
app/admin/         CMS
app/api/contact/   validated enquiry endpoint
components/        ui.tsx (Photo, PageHero, …), motion.tsx (Reveal, Counter), header, footer, timeline, contact form
lib/content.ts     default content + types (source of truth for the CMS schema)
lib/get-content.ts server-side loader (Supabase overrides → defaults)
supabase/schema.sql
```
