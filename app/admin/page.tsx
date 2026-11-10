"use client";

import { useEffect, useState } from "react";
import { createClient, type Session } from "@supabase/supabase-js";
import { ArrowDown, ArrowUp, Plus, Trash2, Upload } from "lucide-react";
import { defaultContent, type SectionKey } from "@/lib/content";

// Uses the public anon key only. All writes are enforced by Row Level Security (see supabase/schema.sql).
const url = process.env.NEXT_PUBLIC_SUPABASE_URL;
const anon = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY;
const supabase = url && anon ? createClient(url, anon) : null;

type Json = string | number | boolean | null | Json[] | { [k: string]: Json };

const LABELS: Record<SectionKey, string> = {
  profile: "Profile & Biography",
  credentials: "Credentials Strip",
  stats: "Statistics",
  expertise: "Surgical Expertise",
  timeline: "Career Timeline",
  education: "Education",
  registrations: "Registrations",
  awards: "Awards",
  achievements: "Achievements",
  publications: "Publications",
  academicContributions: "Academic Contributions",
  conferences: "Conferences",
  patientArticles: "Patient Articles",
  faqs: "FAQs",
  contact: "Contact Details",
};

export default function AdminPage() {
  const [session, setSession] = useState<Session | null>(null);
  const [isAdmin, setIsAdmin] = useState<boolean | null>(null);

  useEffect(() => {
    if (!supabase) return;
    supabase.auth.getSession().then(({ data }) => setSession(data.session));
    const { data } = supabase.auth.onAuthStateChange((_e, s) => setSession(s));
    return () => data.subscription.unsubscribe();
  }, []);

  useEffect(() => {
    if (!supabase || !session) return setIsAdmin(null);
    supabase
      .from("admins")
      .select("user_id")
      .eq("user_id", session.user.id)
      .maybeSingle()
      .then(({ data }) => setIsAdmin(!!data));
  }, [session]);

  return (
    <div className="min-h-screen bg-ivory text-charcoal">
      <header className="flex items-center justify-between bg-midnight px-6 py-4 text-ivory">
        <p className="font-serif text-2xl">
          Malek <span className="italic text-champagne-light">Gaashan</span> <span className="text-ivory/40">· Content</span>
        </p>
        {session && (
          <button onClick={() => supabase!.auth.signOut()} className="text-xs uppercase tracking-[0.2em] text-ivory/70 hover:text-ivory">
            Sign out
          </button>
        )}
      </header>
      {!supabase ? (
        <Notice title="Supabase is not configured">
          Set <code>NEXT_PUBLIC_SUPABASE_URL</code> and <code>NEXT_PUBLIC_SUPABASE_ANON_KEY</code>, run{" "}
          <code>supabase/schema.sql</code>, then reload. See README → “Content management”.
        </Notice>
      ) : !session ? (
        <Login />
      ) : isAdmin === false ? (
        <Notice title="Not authorised">
          This account is not listed in the <code>admins</code> table. Ask the site owner to add your user ID:{" "}
          <code>{session.user.id}</code>
        </Notice>
      ) : isAdmin ? (
        <Editor />
      ) : (
        <p className="p-10 text-sm text-charcoal/60">Checking access…</p>
      )}
    </div>
  );
}

function Notice({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div className="mx-auto mt-20 max-w-xl border-l border-champagne px-6">
      <h1 className="text-3xl text-midnight">{title}</h1>
      <p className="mt-4 text-sm leading-relaxed text-charcoal/70 [&_code]:bg-stone/60 [&_code]:px-1">{children}</p>
    </div>
  );
}

function Login() {
  const [error, setError] = useState("");
  const [busy, setBusy] = useState(false);
  async function onSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault();
    const f = new FormData(e.currentTarget);
    setBusy(true);
    const { error } = await supabase!.auth.signInWithPassword({ email: String(f.get("email")), password: String(f.get("password")) });
    setBusy(false);
    if (error) setError(error.message);
  }
  return (
    <form onSubmit={onSubmit} className="mx-auto mt-24 max-w-sm space-y-5 px-6">
      <h1 className="text-4xl text-midnight">Sign in</h1>
      <input name="email" type="email" required placeholder="Email" autoComplete="username" className="w-full border border-midnight/20 bg-white px-4 py-3" />
      <input name="password" type="password" required placeholder="Password" autoComplete="current-password" className="w-full border border-midnight/20 bg-white px-4 py-3" />
      {error && <p role="alert" className="text-sm text-red-800">{error}</p>}
      <button disabled={busy} className="w-full bg-midnight py-3 text-xs font-semibold uppercase tracking-[0.2em] text-ivory disabled:opacity-60">
        {busy ? "Signing in…" : "Sign in"}
      </button>
    </form>
  );
}

function Editor() {
  const keys = Object.keys(defaultContent) as SectionKey[];
  const [section, setSection] = useState<SectionKey>("profile");
  const [data, setData] = useState<Record<string, Json>>(defaultContent as unknown as Record<string, Json>);
  const [dirty, setDirty] = useState(false);
  const [msg, setMsg] = useState("");

  useEffect(() => {
    supabase!
      .from("site_content")
      .select("key,value")
      .then(({ data: rows, error }) => {
        if (error) return setMsg(`Load failed: ${error.message}`);
        setData((d) => ({ ...d, ...Object.fromEntries((rows ?? []).map((r) => [r.key, r.value])) }));
      });
  }, []);

  async function save() {
    setMsg("Saving…");
    const { error } = await supabase!
      .from("site_content")
      .upsert({ key: section, value: data[section], updated_at: new Date().toISOString() });
    setMsg(error ? `Save failed: ${error.message}` : "Saved. The public site refreshes within about a minute.");
    if (!error) setDirty(false);
  }

  return (
    <div className="grid min-h-[calc(100vh-64px)] md:grid-cols-[16rem_1fr]">
      <nav className="border-r border-midnight/10 bg-stone/30 p-4">
        <ul className="space-y-1">
          {keys.map((k) => (
            <li key={k}>
              <button
                onClick={() => (!dirty || confirm("Discard unsaved changes?")) && (setSection(k), setDirty(false), setMsg(""))}
                className={`w-full px-3 py-2 text-left text-sm ${k === section ? "bg-midnight text-ivory" : "hover:bg-stone/60"}`}
              >
                {LABELS[k]}
              </button>
            </li>
          ))}
        </ul>
      </nav>
      <main className="p-6 md:p-10">
        <div className="sticky top-0 z-10 -mx-6 mb-8 flex flex-wrap items-center justify-between gap-4 border-b border-midnight/10 bg-ivory/95 px-6 py-4 backdrop-blur md:-mx-10 md:px-10">
          <h1 className="text-3xl text-midnight">{LABELS[section]}</h1>
          <div className="flex items-center gap-4">
            <span className="text-xs text-charcoal/60" role="status">{msg}</span>
            <button onClick={save} disabled={!dirty} className="bg-champagne px-6 py-2.5 text-xs font-semibold uppercase tracking-[0.2em] text-midnight disabled:opacity-40">
              Save
            </button>
          </div>
        </div>
        <Field
          name={section}
          value={data[section]}
          onChange={(v) => {
            setData((d) => ({ ...d, [section]: v }));
            setDirty(true);
          }}
          root
        />
      </main>
    </div>
  );
}

const humanize = (k: string) => k.replace(/([A-Z])/g, " $1").replace(/^./, (c) => c.toUpperCase());
const isImageKey = (k: string) => /^(image|portrait|avatar)$|Image$/.test(k);
const input = "w-full border border-midnight/15 bg-white px-3 py-2 text-sm focus:border-champagne focus:outline-none";

/** Empty copy of an item, used when adding a new entry to a list. */
function blank(v: Json): Json {
  if (Array.isArray(v)) return [];
  if (v && typeof v === "object") return Object.fromEntries(Object.entries(v).map(([k, x]) => [k, blank(x)]));
  return typeof v === "number" ? 0 : typeof v === "boolean" ? false : "";
}

/** Renders a form for any JSON shape: strings, numbers, booleans, objects and lists. */
function Field({ name, value, onChange, root }: { name: string; value: Json; onChange: (v: Json) => void; root?: boolean }) {
  const label = !root && <span className="mb-1.5 block text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-charcoal/60">{humanize(name)}</span>;

  if (typeof value === "boolean")
    return (
      <label className="flex items-center gap-3 text-sm">
        <input type="checkbox" checked={value} onChange={(e) => onChange(e.target.checked)} /> {humanize(name)}
      </label>
    );
  if (typeof value === "number")
    return (
      <label className="block">
        {label}
        <input type="number" className={input} value={value} onChange={(e) => onChange(Number(e.target.value))} />
      </label>
    );
  if (typeof value === "string" || value === null) {
    const v = value ?? "";
    if (isImageKey(name)) return <ImageField label={label} value={v} onChange={onChange} />;
    return (
      <label className="block">
        {label}
        {v.length > 90 ? (
          <textarea className={`${input} min-h-28`} value={v} onChange={(e) => onChange(e.target.value)} />
        ) : (
          <input className={input} value={v} onChange={(e) => onChange(e.target.value)} />
        )}
      </label>
    );
  }
  if (Array.isArray(value)) {
    const set = (i: number, v: Json) => onChange(value.map((x, j) => (j === i ? v : x)));
    const move = (i: number, d: number) => {
      const a = [...value];
      [a[i], a[i + d]] = [a[i + d], a[i]];
      onChange(a);
    };
    return (
      <fieldset className="space-y-3">
        {label}
        {value.map((item, i) => (
          <div key={i} className="flex gap-2 border border-midnight/10 bg-white/50 p-3">
            <div className="flex-1 space-y-4">
              <Field name={typeof item === "object" ? `${humanize(name)} ${i + 1}` : `Item ${i + 1}`} value={item} onChange={(v) => set(i, v)} root={typeof item !== "object"} />
            </div>
            <div className="flex flex-col gap-1 text-charcoal/50">
              <button aria-label="Move up" disabled={i === 0} onClick={() => move(i, -1)} className="p-1 hover:text-midnight disabled:opacity-30"><ArrowUp className="size-4" /></button>
              <button aria-label="Move down" disabled={i === value.length - 1} onClick={() => move(i, 1)} className="p-1 hover:text-midnight disabled:opacity-30"><ArrowDown className="size-4" /></button>
              <button aria-label="Remove" onClick={() => confirm("Remove this item?") && onChange(value.filter((_, j) => j !== i))} className="p-1 hover:text-red-700"><Trash2 className="size-4" /></button>
            </div>
          </div>
        ))}
        <button onClick={() => onChange([...value, value.length ? blank(value[0]) : ""])} className="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.16em] text-champagne hover:text-midnight">
          <Plus className="size-4" /> Add
        </button>
      </fieldset>
    );
  }
  return (
    <div className="space-y-5">
      {!root && <p className="font-serif text-xl text-midnight">{name}</p>}
      {Object.entries(value).map(([k, v]) => (
        <Field key={k} name={k} value={v} onChange={(nv) => onChange({ ...value, [k]: nv })} />
      ))}
    </div>
  );
}

function ImageField({ label, value, onChange }: { label: React.ReactNode; value: string; onChange: (v: Json) => void }) {
  const [busy, setBusy] = useState(false);
  async function upload(file: File) {
    setBusy(true);
    const path = `${Date.now()}-${file.name.replace(/[^\w.-]/g, "_")}`;
    const { error } = await supabase!.storage.from("media").upload(path, file, { contentType: file.type });
    setBusy(false);
    if (error) return alert(`Upload failed: ${error.message}`);
    onChange(supabase!.storage.from("media").getPublicUrl(path).data.publicUrl);
  }
  return (
    <div>
      {label}
      <div className="flex items-center gap-3">
        {/* eslint-disable-next-line @next/next/no-img-element */}
        {value ? <img src={value} alt="" className="size-16 border border-midnight/10 object-cover" /> : <span className="size-16 bg-surgical" />}
        <input className={input} value={value} placeholder="Image URL (or upload)" onChange={(e) => onChange(e.target.value)} />
        <label className="flex cursor-pointer items-center gap-2 whitespace-nowrap border border-midnight/20 px-3 py-2 text-xs hover:border-champagne">
          <Upload className="size-4" /> {busy ? "Uploading…" : "Upload"}
          <input type="file" accept="image/*" className="sr-only" onChange={(e) => e.target.files?.[0] && upload(e.target.files[0])} />
        </label>
      </div>
    </div>
  );
}
