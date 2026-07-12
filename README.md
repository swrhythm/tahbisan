# Tahbisan — Diakon & Imam

A web app for a diocese/seminary to publish upcoming ordination ("tahbisan")
events, let visitors browse each candidate's public profile (schedule,
biography, gift wishlist), and let candidates ("frater") and admins manage
that content themselves.

Built with Laravel + SQLite + Tailwind, designed to run on ordinary shared
hosting (cPanel-style) with no database server, no queue worker, and no
long-running processes required.

## Features

- **Public site** — landing page listing upcoming ordinations, event detail
  page listing candidates, candidate profile page (schedule / biography /
  wishlist tabs) with a "I want to give this" flow for wishlist items.
- **Frater/Diakon dashboard** — each candidate logs in and manages their own
  schedule items, biography, and wishlist.
- **Admin dashboard** — create ordination events, create candidate accounts,
  delete events/candidates, see who has claimed which wishlist item.
- All data lives in a single SQLite file — nothing else to provision.

## Local development

Requires PHP 8.2+, Composer, and Node 18+.

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed   # seeds demo events/candidates + an Admin (admin123)
npm run build                # or `npm run dev` while developing
php artisan serve
```

Demo logins after seeding: Admin password `admin123`; candidate
`Yohanes Adi Nugroho`, password `diakon123` (pick the name from the login
dropdown).

## Deploying to shared hosting

The goal is: **upload one zip, extract it, done.** `vendor/` and the built
front-end assets (`public/build/`) are already inside the release zip, so
the hosting account needs neither Composer nor Node — only PHP with the
`pdo_sqlite` extension (standard on virtually every PHP 8.1+ shared host).

### 1. Build the release zip

From your own machine (or this repo), with a clean, committed working tree:

```bash
scripts/build-release.sh [admin-password]
```

This produces `tahbisan-shared-hosting.zip` containing the full app with
dependencies installed, assets built, the SQLite database created and
migrated, and one `Admin` account seeded (password `admin-password`,
defaults to `admin123` if omitted — **change it immediately**, see step 4).

### 2. Upload and extract

Upload the zip to your hosting account (via cPanel File Manager or FTP) into
a folder **outside** your public web root, e.g. `~/tahbisan-app`, and
extract it there. Never extract it directly into `public_html` — the app
folder contains PHP source and the SQLite database, which must not be
web-accessible.

### 3. Point your domain at `public/`

**If your host lets you set a custom document root** (most cPanel hosts do,
via Domains → Manage, or when creating an addon/sub-domain): point the
domain's document root at `~/tahbisan-app/public`. That's it — skip to
step 4.

**If your host only serves from `public_html`** (no custom document root
option): copy the *contents* of `tahbisan-app/public/` into `public_html/`
(everything: `index.php`, `.htaccess`, `build/`, `favicon.ico`, `robots.txt`),
then edit the copied `public_html/index.php` and change the two `require`
paths so they point at the app folder instead of `public_html`'s own parent:

```php
require __DIR__.'/../tahbisan-app/vendor/autoload.php';
$app = require_once __DIR__.'/../tahbisan-app/bootstrap/app.php';
```

(adjust `tahbisan-app` to whatever folder name you actually used).

### 4. Configure and secure

- Edit `~/tahbisan-app/.env` (plain text file, editable in File Manager) and
  set `APP_URL` to your real domain, e.g. `https://tahbisan.paroki.org`.
- Change the admin password immediately if you used the default. If your
  host gives you SSH/Terminal access:
  ```bash
  cd ~/tahbisan-app
  php artisan admin:password "a-strong-new-password"
  ```
  Without shell access, rebuild the release zip locally with a custom
  password (`scripts/build-release.sh 'a-strong-new-password'`) before
  uploading, or ask someone with SSH access to run the command above.
- Make sure `storage/` and `bootstrap/cache/` are writable by the web server
  (the release zip ships them at `775`; if your host resets permissions on
  extraction, `chmod -R 775 storage bootstrap/cache` via File Manager or SSH).
- The `database/database.sqlite` file must also be writable by the web
  server and must **not** be reachable over HTTP — it isn't, as long as it
  stays outside your document root as described above.

### Updating the site later

Rebuild a new release zip and re-upload it, but **do not overwrite**
`.env` or `database/database.sqlite` on the server — those hold your real
configuration and data. Upload everything else (`app/`, `vendor/`,
`public/`, `resources/`, `routes/`, etc.) over the old copy, keeping the
live `.env` and `database/database.sqlite` in place.

## Admin password command

Create or reset an admin login at any time (requires shell access):

```bash
php artisan admin:password "new-password" --name="Admin"
```
