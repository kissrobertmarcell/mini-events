# Mini Events

Modern eseménykezelő rendszer Laravel és Vue 3 használatával.

## Leírás

A Mini Events egy teljes körű eseménykezelő alkalmazás, ahol a felhasználók létrehozhatnak, szerkeszthetnek és törölhetnek eseményeket, valamint jelentkezhetnek mások által szervezett eseményekre. Az alkalmazás támogatja a képek feltöltését, email értesítéseket és race condition kezelést.

## Főbb funkciók

- **Nyilvános eseménylista**: Jövőbeli események böngészése belépés nélkül, 6 esemény oldalanként
- **Eseménykezelés**: Létrehozás, szerkesztés, soft delete (csak bejelentkezett felhasználóknak)
- **Jelentkezés**: Eseményekre való jelentkezés duplikáció és race condition védelemmel
- **Email értesítések**: Megerősítő email a jelentkezőnek és értesítés az esemény szervezőjének
- **Képfeltöltés**: Opcionális eseményképek, maximum 3MB, placeholder ha hiányzik

## Technológiai stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Vue 3 + TypeScript + Inertia.js
- **Stílus**: Tailwind CSS
- **Adatbázis**: MySQL vagy SQLite
- **Tesztelés**: PHPUnit

## Telepítés

1. **Függőségek telepítése**
   ```bash
   composer install
   npm install
   ```

2. **Környezeti változók beállítása**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Adatbázis beállítása**
   
   Frissítsd a `.env` fájlt az adatbázis adataiddal:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mini_events
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   Futtasd a migrációkat:
   ```bash
   php artisan migrate
   ```

4. **Storage link létrehozása**
   ```bash
   php artisan storage:link
   ```

5. **Asset-ek buildelése**
   ```bash
   npm run build
   ```
   
   Fejlesztéshez (hot reload):
   ```bash
   npm run dev
   ```

6. **Szerver indítása**
   ```bash
   php artisan serve
   ```

Az alkalmazás elérhető: `http://localhost:8000`

## Email beállítás

Az email értesítésekhez állítsd be a `.env` fájlban:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="Mini Events"
```

Fejlesztéshez használj Mailtrap.io-t vagy MailHog-ot.

A sorban várakozó emailek feldolgozásához:
```bash
php artisan queue:work
```

## Tesztek futtatása

```bash
php artisan test
```

## Főbb implementációs részletek

- **Race condition kezelés**: Adatbázis tranzakciók `lockForUpdate()` használatával
- **Soft delete**: Az események soft delete-tel törlődnek, így megmarad az adatintegritás
- **Eager loading**: Optimalizált lekérdezések relációk előtöltésével
- **Dátumformázás**: `YYYY.MM.DD. HH:mm` formátum (pl. `2024.05.20. 18:00`)

## Követelmények

- PHP 8.2+
- Composer
- Node.js 18+
- SQLite

## Licenc
