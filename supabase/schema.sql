-- Run once in the Supabase SQL editor.

-- Editable site content: one row per section (key matches lib/content.ts top-level keys).
create table if not exists public.site_content (
  key text primary key,
  value jsonb not null,
  updated_at timestamptz not null default now()
);

-- Users allowed to edit. Add rows manually after creating the user in Auth.
create table if not exists public.admins (
  user_id uuid primary key references auth.users on delete cascade
);

-- Contact form submissions (inserted server-side with the service role key only).
create table if not exists public.enquiries (
  id bigint generated always as identity primary key,
  name text not null,
  email text not null,
  subject text not null,
  message text not null,
  created_at timestamptz not null default now()
);

alter table public.site_content enable row level security;
alter table public.admins enable row level security;
alter table public.enquiries enable row level security;

create or replace function public.is_admin() returns boolean
language sql stable security definer set search_path = public as $$
  select exists (select 1 from public.admins where user_id = auth.uid());
$$;

create policy "content readable by everyone" on public.site_content for select using (true);
create policy "content writable by admins" on public.site_content for all to authenticated
  using (public.is_admin()) with check (public.is_admin());

create policy "admins can see themselves" on public.admins for select to authenticated using (user_id = auth.uid());

create policy "enquiries readable by admins" on public.enquiries for select to authenticated using (public.is_admin());
-- No insert policy: only the service role (server) can insert enquiries.

-- Public image bucket for uploads from /admin.
insert into storage.buckets (id, name, public) values ('media', 'media', true) on conflict (id) do nothing;

create policy "admins upload media" on storage.objects for insert to authenticated
  with check (bucket_id = 'media' and public.is_admin());
create policy "admins update media" on storage.objects for update to authenticated
  using (bucket_id = 'media' and public.is_admin());
create policy "admins delete media" on storage.objects for delete to authenticated
  using (bucket_id = 'media' and public.is_admin());

-- After creating your admin user in Authentication > Users:
-- insert into public.admins (user_id) values ('<user-uuid>');
