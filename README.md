# PIUS ACADEMY

Privatna platforma za online kurseve izrađena kao Laravel 12 monolit. Backend, Blade frontend, Tailwind CSS stilovi i JavaScript nalaze se u jednom repozitoriju i zajedno se objavljuju.

## Tehnologije

- PHP 8.3+
- Laravel 12
- Blade i Tailwind CSS 4
- Vite
- MySQL 8+
- Vimeo embed i automatski oEmbed thumbnaili

## Funkcionalnosti

- administratorski i učenički nalozi bez javne registracije
- upravljanje učenicima, statusima i lozinkama
- upravljanje kursevima, lekcijama i dodjelama pristupa
- Vimeo URL, iframe i unlisted privacy hash podrška
- automatsko preuzimanje naslovne slike iz Vimeo videa
- prikaz samo dodijeljenih i aktivnih kurseva učeniku
- evidencija prijava, novih IP adresa i uređaja
- zaboravljena lozinka i siguran jednokratni reset link
- responzivan crno-bež PIUS ACADEMY interfejs
- CSP i standardni sigurnosni HTTP headeri

## Lokalna instalacija

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Kreirajte MySQL bazu i prilagodite `DB_*` vrijednosti u `.env`, zatim pokrenite:

```bash
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

Aplikacija će standardno biti dostupna na `http://127.0.0.1:8000`.

## Početni administrator

- Email: `admin@example.com`
- Lozinka: `ChangeMe123!`

Ovaj nalog kreira `AdminSeeder`. Lozinku obavezno promijenite prije produkcijskog korištenja.

## Email i reset lozinke

Lokalni `.env.example` koristi `MAIL_MAILER=log`. Za stvarno slanje reset linkova postavite SMTP vrijednosti `MAIL_*` u produkcijskom `.env` fajlu.

## Sigurnost

- admin i student rute imaju zasebne role i status provjere
- neaktivni nalozi su blokirani i nakon postojeće prijave
- svaki pristup kursu provjerava dodjelu i aktivni status
- lekcija mora pripadati kursu iz URL-a
- login je zaštićen CSRF-om, regeneracijom sesije i rate limitom
- reset token vrijedi 60 minuta i može se iskoristiti jednom
- Vimeo thumbnail prihvata se samo s pouzdane HTTPS `vimeocdn.com` domene
- sesije su enkriptovane; u produkciji uključite HTTPS i `SESSION_SECURE_COOKIE=true`

Vimeo embed nije DRM. Za privatne ili unlisted videe podesite Vimeo domain-level embed privacy za produkcijski domen.

## Testovi

```bash
php artisan test
composer audit
npm audit --omit=dev
```

Testovi koriste SQLite bazu u memoriji i pokrivaju autentifikaciju, reset lozinke, admin CRUD, dodjele kurseva, više dostupnih kurseva, zabrane pristupa, pripadnost lekcija, Vimeo obradu i sigurnosne headere.

## Produkcijska objava

Najmanje postavite:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://vas-domen.example
SESSION_SECURE_COOKIE=true
```

Nakon deploymenta pokrenite migracije i optimizaciju:

```bash
php artisan migrate --force
php artisan optimize
npm ci
npm run build
```
