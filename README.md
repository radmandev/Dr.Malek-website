# Dr. Malek Gaashan — Personal Medical Website

Plain PHP 8.1+ · MySQL / MariaDB · Tailwind CSS v4 (precompiled) · vanilla JavaScript. No framework, no Composer, no Node on the server.

## Run locally

```bash
cp config.example.php config.php        # optional: add database + mail settings
php -S localhost:8000 -t public public/index.php
```

Open http://localhost:8000.

The site works with **no configuration**: it renders the bundled content in `app/content.php`.
Without a database, `/admin` explains how to connect it. The contact form emails enquiries if `mail.to` is set; if neither the database nor email is configured, it says the service is not yet connected and never pretends to send.

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
| `/admin` | Content management, enquiries inbox, account — not indexed |

SEO: per-page titles/descriptions, canonical URLs, Open Graph image (`public/og-image.png`), `Physician` JSON-LD, `/sitemap.xml`, `/robots.txt`.

## Deploying

Requirements: PHP 8.1+ with `pdo_mysql` and `mbstring` (standard on most hosts), MySQL 5.7+ or MariaDB 10.3+, Apache with `mod_rewrite` (or nginx, below). The site must be served from the root of a domain or subdomain.

1. Upload the whole folder.
2. **Web root:** point the domain's document root at `public/`. If your host only serves the folder itself (e.g. `public_html`), upload the project there and the root `.htaccess` routes everything into `public/` — `app/`, `templates/` and `config.php` stay unreachable either way.
3. Create a MySQL database and user (cPanel → MySQL Databases).
4. Copy `config.example.php` to `config.php` and fill in `site_url` (e.g. `https://www.example.com`), the `db` settings and `mail`.
5. Make `public/uploads/` writable by the web server (usually already true; otherwise `chmod 755`).
6. Visit `/admin`. The first visit opens a setup page that creates the database tables and the administrator account (a username and a password of at least 10 characters; usernames are not case-sensitive). **Do this straight after uploading** — the setup page is open to anyone until the first account exists.

nginx equivalent of the `.htaccess` rules (with `root …/public;`):

```nginx
location / { try_files $uri /index.php$is_args$args; }
location ~ ^/uploads/.*\.php$ { deny all; }
location ~ \.php$ { include fastcgi_params; fastcgi_param SCRIPT_FILENAME $document_root/index.php; fastcgi_pass unix:/run/php/php-fpm.sock; }
```

## Content management

Sign in at `/admin`.

- **Overview** — one card per page of the website, listing what can be edited there and when each part was last edited, plus the latest enquiries.
- **Content** — every section of the site (biography, timeline, expertise, publications, conferences, achievements, patient articles, FAQs, contact details, images) is editable as a form with plain-English labels and hints. Lists open as collapsible cards that can be reordered, added to and deleted; photographs show a live preview with framing controls (focus point and zoom) and accept drag-and-drop uploads. A save bar appears only when there are unsaved changes (Save, Discard, or Ctrl/⌘ S). Saving writes that section to the `site_content` table as JSON, replacing the bundled default; changes are live immediately. **Restore original content** removes a section's saved version so the site shows the bundled default again. Images upload to `public/uploads/` (JPEG, PNG, WebP or GIF, up to 8 MB).
- **Enquiries** — every contact-form submission, newest first, with reply-by-email links and delete.
- **Password** — change the password.

Forgotten password, or need another administrator? With SSH access run `php bin/admin-user.php "username"` (quote it if it contains spaces) — it creates the account or resets its password.

Security model: passwords are bcrypt-hashed; sessions are HTTP-only, SameSite cookies scoped to `/admin` that expire after 4 hours idle; every admin form and API call is CSRF-protected; sign-in is throttled to 5 failures per IP per 15 minutes; uploads are verified as real images and stored under random names in a folder that refuses to execute scripts. Public pages set no cookies.

## Contact form

Each enquiry is validated, stored in the `enquiries` table and emailed to `mail.to` with PHP `mail()`; the email's Reply-To is the enquirer, so you can answer directly. A hidden honeypot field catches bots, and each IP address can send at most 5 enquiries an hour. Without JavaScript the form still works as a normal form post.

If your host's `mail()` is unreliable, enquiries are still saved and visible in `/admin → Enquiries`. To send through SMTP instead, replace `send_enquiry_email()` in `app/contact.php` (e.g. with PHPMailer).

## Styles

Pages use Tailwind CSS classes. The compiled stylesheet `public/assets/css/site.css` is committed, so the server needs no build step. After adding or changing classes in `templates/`, `app/` or `public/assets/js/`, rebuild it from `resources/css/input.css` with either:

```bash
npx @tailwindcss/cli@4 -i resources/css/input.css -o public/assets/css/site.css --minify
# or, without Node: download the standalone `tailwindcss` binary for your OS from
# https://github.com/tailwindlabs/tailwindcss/releases into this folder, then:
./tailwindcss -i resources/css/input.css -o public/assets/css/site.css --minify
```

Fonts (Cormorant Garamond, DM Sans) are self-hosted in `public/assets/fonts/`, so no visitor data goes to Google.

## Missing assets & information to supply

Nothing below has been invented — each appears on the site as a clearly labelled placeholder until provided.

- **Photography:** Da Vinci system, hospital architecture, conference — for the remaining expertise section and the AAU feature. Only approved, non-identifiable imagery. Upload via `/admin`, or place files in `public/images/` and reference them as `/images/<file>.jpg`.
- **Publications:** journal names, DOIs / links for both 2018 papers.
- **AAU 2026:** exact presentation date.
- **Contact:** verified institutional address, professional email, secretary number, GMC number.
- **Registrations:** GMC details and any other registrations.
- **Qualification years** for Arab Board and Jordan Board of Urology.

## Structure

```
public/                 web root
  index.php             front controller: routes, page titles/descriptions, robots.txt, sitemap.xml
  assets/               compiled CSS, site.js (animations, menu, timeline, form), admin.js (editor), fonts
  images/               bundled photography
  uploads/              images uploaded through /admin (not in git)
app/
  content.php           default content — the source of truth for the CMS schema
  db.php                PDO connection, content loader (database overrides → defaults)
  components.php        photo(), page_hero(), button_link(), counter(), … (escape all output)
  contact.php           enquiry validation, storage, email
  admin.php             /admin: auth, setup, editor API, uploads, enquiries, account
templates/              layout, header/footer, timeline, one file per page, admin screens
resources/css/input.css Tailwind source
database/schema.sql     tables (also created automatically by /admin setup)
bin/admin-user.php      create an administrator / reset a password from the command line
config.example.php      settings template → copy to config.php
```
