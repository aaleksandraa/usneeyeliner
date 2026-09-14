Napravi kompletan, jednostavan i čist Laravel web projekat za privatne online kurseve.

Tehnologije:

* Laravel 12
* PHP 8.3+
* MySQL
* Blade
* Tailwind CSS
* Laravel authentication sa session/cookie loginom
* Eloquent ORM
* Vimeo embedded video player

Nemoj koristiti React, Vue, Next.js niti odvojeni frontend/backend.

Želim klasičnu Laravel aplikaciju gdje su backend, Blade frontend i autentifikacija unutar jednog projekta.

Aplikacija treba biti jednostavna za održavanje i kasnije proširivanje.

# 1. OSNOVNI KONCEPT

Postoje samo dvije vrste korisnika:

* admin
* student

Ne postoji javna registracija.

Samo admin može kreirati učenike.

Student dobija email i početnu lozinku od administratora i tim podacima se prijavljuje.

Sistem nije klasični veliki LMS.

Potrebne su samo ove funkcije:

Admin:

* login
* upravljanje učenicima
* upravljanje kursevima
* dodavanje Vimeo video lekcija
* dodjeljivanje kurseva učenicima
* pregled login aktivnosti učenika
* pregled IP adresa i uređaja sa kojih se učenik prijavljuje

Student:

* login
* pregled svojih kurseva
* otvaranje kursa
* gledanje Vimeo lekcija
* izmjena svog profila
* izmjena email adrese
* izmjena lozinke

# 2. BAZA PODATAKA

Koristi MySQL.

Laravel `.env` treba koristiti standardnu MySQL konfiguraciju:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=course_platform
DB_USERNAME=root
DB_PASSWORD=

Koristi Laravel migrations.

## users

Tabela `users`:

* id
* first_name
* last_name
* email
* password
* role
* status
* created_at
* updated_at

`role` može biti:

* admin
* student

`status` može biti:

* active
* inactive

Email mora biti unique.

Password obavezno hashovati korištenjem Laravel Hash sistema.

Ne praviti posebne tabele za admina i studenta.

Oboje su `users`, razlikuju se preko `role`.

# 3. ADMIN

Admin mora imati svoj dashboard.

Admin rute moraju biti zaštićene middlewareom.

Napravi middleware ili odgovarajući Laravel authorization sistem kojim se provjerava:

role === admin

Student nikada ne smije pristupiti administratorskim rutama čak ni direktnim unosom URL-a.

Admin sidebar:

* Dashboard
* Students
* Courses
* Logout

# 4. ADMIN DASHBOARD

Na dashboardu prikazati jednostavne statistike:

* ukupan broj učenika
* broj aktivnih učenika
* ukupan broj kurseva
* broj prijava danas

Ne treba praviti grafikone niti komplikovanu analitiku.

# 5. UPRAVLJANJE UČENICIMA

Admin stranica:

/admin/students

Prikazuje tabelu učenika.

Kolone:

* ime i prezime
* email
* status
* broj dodijeljenih kurseva
* posljednja prijava
* actions

Admin može:

* dodati učenika
* editovati učenika
* deaktivirati učenika
* aktivirati učenika
* obrisati učenika
* promijeniti/resetovati njegovu lozinku
* otvoriti detalje učenika

Kod kreiranja učenika admin unosi:

* ime
* prezime
* email
* početnu lozinku

Role automatski postaviti na:

student

Status:

active

# 6. DETALJI UČENIKA

Ruta primjer:

/admin/students/{student}

Stranica treba imati nekoliko sekcija.

## Basic information

Prikazati:

* ime
* prezime
* email
* status
* datum kreiranja naloga
* datum posljednje prijave

## Assigned Courses

Prikazati sve kurseve koji su trenutno dodijeljeni učeniku.

Admin mora moći:

* dodati kurs učeniku
* ukloniti kurs učeniku

Jedan učenik može imati više kurseva.

Jedan kurs može imati više učenika.

Koristi many-to-many relaciju.

## Login Activity

Prikazati posljednje login aktivnosti učenika.

Kolone:

* datum i vrijeme
* IP adresa
* browser/device
* oznaka ako je nova IP adresa
* oznaka ako izgleda kao novi uređaj

Najnovije prijave prikazivati prve.

# 7. KURSEVI

Admin ruta:

/admin/courses

Admin može:

* kreirati kurs
* editovati kurs
* deaktivirati kurs
* aktivirati kurs
* obrisati kurs

Tabela `courses`:

* id
* title
* slug
* description
* image nullable
* status
* created_at
* updated_at

Status:

* active
* inactive

Slug generisati automatski iz naziva kursa.

Primjer:

title:
Kurs usne

slug:
kurs-usne

# 8. LEKCIJE

Svaki kurs može imati jednu ili više lekcija.

Tabela:

course_lessons

Kolone:

* id
* course_id
* title
* description nullable
* vimeo_url
* sort_order
* created_at
* updated_at

Admin na stranici kursa može:

* dodati lekciju
* editovati lekciju
* obrisati lekciju
* mijenjati redoslijed lekcija

Lekcije sortirati prema:

sort_order ASC

Nije potreban drag-and-drop ako komplikuje implementaciju.

Dovoljno je numeric polje `sort_order`.

# 9. VIMEO VIDEO

Video fajlovi se NE uploaduju na server.

Svi video sadržaji se nalaze na Vimeo.

Admin unosi Vimeo URL.

Primjer:

https://vimeo.com/123456789

ili drugi validan Vimeo URL.

Napravi helper/service koji iz URL-a izvlači Vimeo video ID.

Na studentskoj stranici renderovati:

iframe

koristeći:

https://player.vimeo.com/video/{VIDEO_ID}

Video treba biti responsive.

Koristi aspect ratio približno:

16:9

Ne koristiti lokalni video storage.

Ako Vimeo URL nije validan, prikazati validation error.

Podržati standardne Vimeo URL formate koliko je razumno.

# 10. ASSIGNED COURSES

Napraviti pivot tabelu:

course_user

Kolone:

* id
* course_id
* user_id
* created_at
* updated_at

Dodati unique constraint na:

course_id + user_id

Laravel modeli trebaju imati relacije:

User:

courses()

Course:

students()

# 11. STUDENT DASHBOARD

Nakon logina student ide na:

/dashboard

ili:

/student/dashboard

Prikazati samo njegove dodijeljene kurseve.

Naslov:

My Courses

ili odgovarajući lokalizovani naziv.

Svaki kurs prikazati kao card sa:

* slikom ako postoji
* nazivom
* kratkim opisom
* dugmetom "Open Course"

Ako nema kurseva, prikazati jednostavnu poruku da trenutno nema dodijeljenih kurseva.

# 12. OTVARANJE KURSA

Student može otvoriti samo kurs koji mu je dodijeljen.

Ruta može biti:

/courses/{course:slug}

Backend mora prije prikaza kursa provjeriti da li je trenutnom korisniku kurs dodijeljen.

Nije dovoljno sakriti kurs u frontendu.

Ako student pokuša direktno otvoriti URL kursa koji mu nije dodijeljen:

vrati:

403 Forbidden

ili odgovarajuću access denied stranicu.

Na stranici kursa prikazati:

* naziv
* opis
* listu lekcija

Klikom na lekciju otvara se video.

Može biti ruta:

/courses/{course:slug}/lessons/{lesson}

Ponovo provjeriti:

* da lesson pripada kursu
* da je kurs dodijeljen trenutno prijavljenom studentu

# 13. STUDENT PROFIL

Student ima stranicu:

/profile

Može mijenjati:

* ime
* prezime
* email

Email mora ostati unique.

Student može posebno promijeniti lozinku.

Kod promjene lozinke mora unijeti:

* current password
* new password
* new password confirmation

Provjeriti trenutnu lozinku prije promjene.

Nova lozinka mora biti hashovana.

# 14. LOGIN LOGOVI

Želim da se evidentira svaki uspješan login učenika.

Napraviti tabelu:

user_login_logs

Kolone:

* id
* user_id
* ip_address
* user_agent
* device_hash nullable
* created_at
* updated_at

Prilikom uspješnog logina sačuvati:

* user ID
* request IP adresu
* User-Agent header
* jednostavni hashed device identifier

Device hash može biti napravljen iz kombinacije npr:

user_agent + određeni stabilni request podaci

ali nemoj koristiti invasive fingerprinting.

Svrha je samo okvirno prepoznavanje novih uređaja.

# 15. DETEKCIJA NOVE IP ADRESE

Kod prikaza login loga administratoru:

ako se IP adresa pojavljuje prvi put za tog korisnika, označiti:

New IP

Ako je device hash novi:

New Device

Nemoj automatski blokirati učenika.

IP adresa nije dovoljno pouzdana za automatsko blokiranje jer:

* mobilni internet mijenja IP
* korisnik može koristiti različite Wi-Fi mreže
* ISP može koristiti dinamičke IP adrese
* VPN može promijeniti IP

Ovo je samo administratorski audit log.

# 16. LAST LOGIN

Možeš ili:

A)
izračunavati posljednji login iz `user_login_logs`

ili

B)
dodati u users:

last_login_at
last_login_ip

Ako dodavanje ovih polja pojednostavljuje prikaz, koristi opciju B.

Preporučeno:

users:

* last_login_at nullable
* last_login_ip nullable

A login logove i dalje čuvati u `user_login_logs`.

# 17. AUTHENTICATION

Koristi Laravel session authentication.

Možeš koristiti Laravel Breeze sa Blade stackom ako to pojednostavljuje projekat.

NE treba:

* public registration
* forgot password email flow
* social login
* Google login
* API token authentication
* Sanctum za frontend

Login stranica treba imati samo:

* email
* password
* remember me opcionalno
* login button

Ako je status korisnika:

inactive

ne dozvoli login.

# 18. REDIRECT NAKON LOGINA

Ako je role:

admin

redirect:

/admin

Ako je:

student

redirect:

/dashboard

# 19. LOGIN SECURITY

Implementirati Laravel best practices:

* Hash passwords
* CSRF protection
* session regeneration nakon uspješnog logina
* session invalidation na logout
* validation requestova
* login rate limiting
* route authorization
* backend permission checks
* protection protiv mass assignmenta
* Eloquent ORM
* Form Request classes gdje imaju smisla

Ne praviti nepotrebno kompleksan permission package.

Ne treba Spatie Permission.

Dovoljna je jednostavna role kolona i middleware/policies.

# 20. MODELI

Napraviti modele:

User
Course
CourseLesson
UserLoginLog

Relacije:

User:

courses()
loginLogs()

Course:

students()
lessons()

CourseLesson:

course()

UserLoginLog:

user()

# 21. CONTROLLERS

Organizuj controllere smisleno.

Primjer:

Admin/DashboardController
Admin/StudentController
Admin/CourseController
Admin/CourseLessonController
Admin/StudentCourseController

Student/DashboardController
Student/CourseController
Student/ProfileController

Auth/LoginController

Može se prilagoditi Laravel standardima ako postoji elegantnija struktura.

Nemoj praviti jedan ogroman controller.

# 22. FORM REQUEST VALIDATION

Koristi Form Request klase gdje imaju smisla.

Na primjer:

StoreStudentRequest
UpdateStudentRequest

StoreCourseRequest
UpdateCourseRequest

StoreCourseLessonRequest
UpdateCourseLessonRequest

UpdateProfileRequest
UpdatePasswordRequest

Validacija mora postojati na backendu.

# 23. UI

Koristi:

Blade
Tailwind CSS

Dizajn neka bude:

* moderan
* čist
* profesionalan
* minimalistički
* responsive

Ne treba komplikovan UI framework.

Admin layout:

desktop:

left sidebar + content

mobile:

collapsible navigation

Admin sidebar:

Dashboard
Students
Courses
Logout

Student navigation:

My Courses
Profile
Logout

# 24. ADMIN STUDENT LIST

Tabela učenika treba imati search.

Search najmanje po:

* first name
* last name
* email

Dodati pagination.

Npr:

20 korisnika po stranici.

# 25. ADMIN COURSE LIST

Prikazati:

* naziv kursa
* status
* broj lekcija
* broj učenika kojima je dodijeljen
* edit
* delete

Dodati pagination ako ima smisla.

# 26. BRISANJE

Prije delete akcije prikazati confirmation.

Kod brisanja kursa pravilno obrisati/detach povezane pivot zapise i lessons prema pravilno definisanim database foreign key pravilima.

Nemoj ostavljati orphan podatke.

Kod brisanja studenta ukloniti njegove:

* course assignments
* login logs

ili koristiti odgovarajući cascade behavior.

# 27. FOREIGN KEYS

Dodati foreign keys i indekse.

Primjeri:

course_lessons.course_id -> courses.id

course_user.course_id -> courses.id

course_user.user_id -> users.id

user_login_logs.user_id -> users.id

Koristi cascade delete tamo gdje je logično.

# 28. SEEDER

Napraviti AdminSeeder.

Kreirati default admin nalog za development.

Primjer:

email:
[admin@example.com](mailto:admin@example.com)

password:
ChangeMe123!

Ime:
Admin

Role:
admin

Status:
active

Nemoj hardkodovati ovaj password izvan seedera.

U README jasno napisati da password treba promijeniti.

# 29. FACTORIES

Po mogućnosti napravi factories za:

User
Course
CourseLesson

da se development baza lako može popuniti testnim podacima.

# 30. TESTOVI

Napravi osnovne Feature testove za najvažnije authorization slučajeve.

Obavezno testirati:

1. student ne može otvoriti admin rute

2. admin može otvoriti admin rute

3. student može otvoriti kurs koji mu je dodijeljen

4. student ne može otvoriti kurs koji mu nije dodijeljen

5. student ne može otvoriti lekciju drugog kursa za koji nema pristup

6. inactive user se ne može prijaviti

7. login učenika kreira login log

8. student ne može mijenjati profil drugog korisnika

Ne treba praviti stotine testova.

Testirati ključnu sigurnosnu logiku.

# 31. README

Napravi kvalitetan README.md sa tačnim koracima instalacije.

Treba sadržavati:

Requirements

* PHP 8.3+
* Composer
* Node.js
* MySQL

Installation:

composer install

cp .env.example .env

php artisan key:generate

podesiti MySQL podatke u .env

php artisan migrate --seed

npm install

npm run build

php artisan serve

Objasniti default admin nalog.

# 32. `.env.example`

Pripremiti dobar `.env.example`.

Koristiti MySQL kao default:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=course_platform
DB_USERNAME=root
DB_PASSWORD=

# 33. STRUKTURA I KVALITET KODA

Važno:

Nemoj over-engineerovati projekat.

Ovo je mali privatni LMS.

Preferiraj Laravel native rješenja.

Ne uvoditi:

* repository pattern bez potrebe
* service layer za trivijalne CRUD operacije
* event sourcing
* CQRS
* microservices
* API između Blade frontenda i Laravel backenda
* Docker ako nije potreban
* Redis ako nije potreban
* queues ako nisu potrebne
* Spatie Permission
* kompleksne JavaScript frameworke

Service/helper ima smisla za Vimeo parsing.

Ostatak neka ostane jednostavan Laravel kod.

# 34. VAŽNA SIGURNOSNA PRAVILA

Nikada ne vjerovati ID-u ili URL-u koji dolazi sa frontenda.

Uvijek na backendu provjeriti vlasništvo/pristup.

Na primjer:

student otvara:

/courses/kurs-usne/lessons/15

nije dovoljno provjeriti samo da lesson ID 15 postoji.

Mora se provjeriti:

* lesson pripada kursu `kurs-usne`
* trenutni student ima taj kurs dodijeljen

Koristi policies, route model binding ili eksplicitne authorization provjere.

# 35. VIMEO SECURITY

Vimeo video će biti private/unlisted gdje je moguće.

Naša aplikacija ne može u potpunosti spriječiti korisnika da tehnički pronađe video URL kroz browser dev tools.

Zato ne predstavljaj Vimeo embed kao DRM zaštitu.

Aplikacija samo kontroliše pristup stranicama i kursevima.

Vimeo domain-level embed privacy će biti dodatno konfigurisana direktno na Vimeo nalogu.

# 36. ŠTA NE TREBA PRAVITI

Ne implementirati:

* registraciju učenika
* ecommerce
* plaćanje
* Stripe
* PayPal
* certifikate
* kvizove
* testove
* bodovanje
* komentare
* chat
* forum
* ocjene
* wishlist
* subscription
* course marketplace
* affiliate sistem
* email marketing
* notifikacije
* naprednu analitiku
* mobile app
* API za vanjske aplikacije
* course progress tracking
* watched video tracking

Ako neka od ovih funkcionalnosti nije eksplicitno tražena, nemoj je dodavati.

# 37. FINALNI USER FLOW

ADMIN:

Login

→ Admin Dashboard

→ Students

→ Add Student

→ unese ime, prezime, email i password

→ Courses

→ Add Course

→ unese naziv i opis

→ Add Lesson

→ unese Vimeo URL

→ otvori učenika

→ Assigned Courses

→ dodijeli jedan ili više kurseva

STUDENT:

Login

→ My Courses

→ vidi samo svoje dodijeljene kurseve

→ Open Course

→ vidi lekcije

→ otvori lekciju

→ Vimeo video player

ADMIN SECURITY VIEW:

Students

→ Student Details

→ Login Activity

→ vidi:

datum
vrijeme
IP adresu
browser/device
New IP
New Device

# 38. NAČIN IMPLEMENTACIJE

Radi projekat fazno.

FAZA 1:

* inicijalizuj Laravel projekat
* podesi MySQL
* authentication
* users migration
* roles
* middleware
* admin seeder

FAZA 2:

* Course model
* CourseLesson model
* migrations
* relationships
* course CRUD

FAZA 3:

* student CRUD
* course assignments
* many-to-many relationship

FAZA 4:

* student dashboard
* authorization
* course/lesson viewing
* Vimeo embed

FAZA 5:

* profile editing
* password editing

FAZA 6:

* login audit log
* IP tracking
* device hash
* admin login activity UI

FAZA 7:

* Feature tests
* polishing UI
* README

Nakon svake faze:

* pokreni migrations
* pokreni relevantne testove
* provjeri da aplikacija radi
* ispravi pronađene greške prije prelaska na sljedeću fazu

# 39. BITNO ZA CURSOR

Nemoj samo opisivati šta treba uraditi.

Implementiraj projekat.

Kreiraj:

* migrations
* models
* controllers
* middleware
* requests
* policies gdje su potrebne
* routes
* Blade views
* Tailwind UI
* seeders
* factories
* tests
* README

Ako projekat već postoji u trenutnom folderu, prvo analiziraj postojeću strukturu i nemoj nepotrebno prepisivati ispravno postojeće dijelove.

Prije velikih izmjena napravi kratak pregled trenutnog stanja projekta.

Nakon implementacije navedi:

1. šta je napravljeno
2. koje migracije postoje
3. koje rute postoje
4. koji testovi su pokrenuti
5. rezultate testova
6. podatke default admin naloga
7. eventualne stvari koje još zahtijevaju ručno podešavanje, npr. Vimeo privacy settings

Prioriteti su:

1. sigurnost
2. jednostavnost
3. pouzdanost
4. čist Laravel kod
5. jednostavno održavanje
6. moderan ali jednostavan UI
