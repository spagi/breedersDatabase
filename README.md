# 🐾 PaW Breeders — Databáze chovatelských stanic psů

Webová aplikace pro správu a vyhledávání chovatelských stanic psů.
Vícejazičná (CS / SK / EN) | Symfony 7.2 | SQLite | Tailwind CSS

---

## Funkce

### Pro veřejnost
- **Vyhledávání** stanic podle plemene, názvu, lokality, zaměření
- **Profil stanice** — popis, psi, vrhy, kontakt
- **Detail vrhu** — rodiče, počty štěňat, dostupnost

### Pro chovatele (po registraci)
- Vytvoření a správa profilu chovatelské stanice
- Správa psů (jméno, pohlaví, tituly, čip, popis)
- Správa vrhů (označení, rodiče, datum, počty štěňat)
- Vícejazyčný popis stanice (CS / SK / EN)

### Administrace (`/admin`)
- Správa všech uživatelů (role, mazání)
- Správa stanic (aktivace, ověření, mazání)
- Správa plemen (CRUD)

---

## Spuštění pomocí Dockeru (doporučeno)

### Požadavky
- Docker 24+
- Docker Compose v2+

### Spuštění

```bash
# 1. Klonovat repositář
git clone <repo-url>
cd breedersDatabase

# 2. Sestavit a spustit
docker-compose up --build -d

# 3. Počkat na zdravý stav (cca 30-60 s při prvním spuštění)
docker-compose ps

# 4. Otevřít aplikaci
open http://localhost:8080
```

### Přihlašovací údaje (demo data)

| Role     | E-mail                     | Heslo      |
|----------|----------------------------|------------|
| Admin    | admin@pawbreeders.cz       | admin123   |
| Chovatel | jana.novakova@email.cz     | heslo123   |
| Chovatel | petr.dvorak@email.cz       | heslo123   |
| Chovatel | marie.horakova@email.sk    | heslo123   |

### Zastavení

```bash
docker-compose down          # zastaví kontejnery
docker-compose down -v       # zastaví + smaže volumes (databázi)
```

---

## Lokální vývoj (bez Dockeru)

### Požadavky
- PHP 8.2+
- Composer 2+
- Node.js 18+ (volitelné — pro assets)
- SQLite

### Instalace

```bash
# 1. Nainstalovat PHP závislosti
composer install

# 2. Nastavit prostředí
cp .env .env.local
# .env.local obsahuje DATABASE_URL s SQLite — není třeba měnit

# 3. Vytvořit databázi a spustit migrace
php bin/console doctrine:schema:create

# 4. Načíst demo data
php bin/console doctrine:fixtures:load

# 5. Zkompilovat assets
php bin/console asset-map:compile

# 6. Spustit vývojový server
php -S localhost:8000 -t public/
```

---

## Architektura

```
src/
├── Controller/
│   ├── Admin/AdminController.php   # Admin panel
│   ├── HomeController.php          # Domovská stránka + dashboard
│   ├── KennelController.php        # Správa stanic, psů, vrhů
│   ├── SearchController.php        # Vyhledávání
│   ├── SecurityController.php      # Login, registrace
│   └── LocaleController.php        # Přepínač jazyka
├── Entity/
│   ├── User.php                    # Uživatel
│   ├── Kennel.php                  # Chovatelská stanice
│   ├── Breed.php                   # Plemeno
│   ├── Dog.php                     # Pes
│   ├── Litter.php                  # Vrh
│   ├── Puppy.php                   # Štěně
│   └── KennelPhoto.php             # Fotografie
├── Form/                           # Symfony formuláře
├── Repository/                     # Doctrine repositories
└── DataFixtures/                   # Demo data

templates/
├── base.html.twig                  # Základní layout (navbar, footer)
├── home/                           # Domovská stránka, dashboard
├── security/                       # Login, registrace
├── kennel/                         # Profil stanice, psi, vrhy
├── search/                         # Vyhledávání
└── admin/                          # Admin panel

translations/
├── messages.cs.yaml                # Čeština
├── messages.en.yaml                # Angličtina
└── messages.sk.yaml                # Slovenština
```

---

## URL přehled

| URL                  | Popis                        |
|----------------------|------------------------------|
| `/`                  | Domovská stránka             |
| `/search`            | Vyhledávání stanic           |
| `/kennel/{slug}`     | Veřejný profil stanice       |
| `/litter/{id}`       | Detail vrhu                  |
| `/login`             | Přihlášení                   |
| `/register`          | Registrace                   |
| `/dashboard`         | Dashboard chovatele          |
| `/kennel/new`        | Vytvoření stanice            |
| `/kennel/edit`       | Úprava stanice               |
| `/admin`             | Admin panel                  |
| `/locale/{cs|sk|en}` | Přepnutí jazyka              |

---

## Technologie

- **Backend:** Symfony 7.2, PHP 8.4, Doctrine ORM
- **Databáze:** SQLite (lehce zaměnitelná za MySQL/PostgreSQL)
- **Frontend:** Twig, vlastní CSS (přírodní paleta), vanilla JS
- **Překlady:** Symfony Translation (CS, SK, EN)
- **Stránkování:** KnpPaginator
- **Kontejnerizace:** Docker + nginx + PHP-FPM + supervisord
