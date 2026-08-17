# Sonar Haat — Multi-Vendor Jewellery Showcase

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php)](https://php.net)
[![PHPStan](https://img.shields.io/badge/PHPStan-Level_5_Passed-4B5563?style=flat-square)](https://phpstan.org)
[![Tests](https://img.shields.io/badge/Tests-88_Passed_(305_assertions)-10B981?style=flat-square)](https://github.com)
[![License](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)

A multi-vendor jewellery showcase application built with Laravel 12, Alpine.js, and Tailwind CSS. Features vendor catalog management, multi-attribute filtering (metals, purities, sizes, gemstones), real-time stock reservations with WebSocket broadcasts, multi-angle studio photography, bulk CSV imports, and multilingual support (English, Hindi, Arabic RTL).

---

## Features

### Multi-Vendor Catalog
- **Vendor Portal:** Vendors manage products, multi-angle images, metal types, purities, sizes, and stone settings.
- **Admin Oversight:** Approval and suspension workflows with automatic cascade updates.
- **Bulk CSV Import:** Chunked queue processing with row validation, error reporting, and sample template downloads.

### Search & Filtering
- **Faceted Filters:** Filter by category, metal (Yellow Gold, White Gold, Rose Gold, Platinum, Silver), purity (24K, 22K, 18K, 14K), stone type (Diamond, Ruby, Emerald, Sapphire), size, price, and stock status.
- **Eager Loaded Queries:** Indexed attributes with optimized relationship loading to prevent N+1 query overhead.

### Real-Time Stock Reservations
- **Concurrency Control:** `lockForUpdate()` pessimistic locking prevents inventory overselling.
- **Temporary Holds:** 15-minute reservation window with automatic expiration via scheduled task (`reservations:expire`).
- **Live Broadcasting:** Real-time WebSocket updates to all users when stock is reserved or released.

### Localization & Interface
- **Multilingual (i18n):** English, Hindi (`हिन्दी`), and Arabic (`العربية`).
- **Bidirectional Layout:** Automatic right-to-left (RTL) mode for Arabic.
- **Theme Support:** Dark and light modes with custom color tokens.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 12 (PHP 8.3+) |
| Database & Cache | MySQL 8.0+ / SQLite, Redis |
| WebSockets | Laravel Reverb / Pusher |
| Queue Worker | Redis / Database / SQS |
| Frontend | Blade, Alpine.js, Tailwind CSS, Vite |
| Code Quality | PHPStan / Larastan (Level 5), Laravel Pint (PSR-12) |
| Testing | PHPUnit (88 tests, 305 assertions) |

---

## Getting Started

### Using Docker (Laravel Sail)

```bash
# 1. Clone the repository
git clone https://github.com/i-priyanshuverma/laravel-jewellery-showcase.git
cd laravel-jewellery-showcase

# 2. Copy environment configuration
cp .env.example .env

# 3. Start containers (App, MySQL, Redis)
./vendor/bin/sail up -d

# 4. Generate application key and run migrations with seed data
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed

# 5. Compile assets
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

The app is accessible at `http://localhost`.

---

### Using Native PHP & Composer

```bash
# 1. Clone repository
git clone https://github.com/i-priyanshuverma/laravel-jewellery-showcase.git
cd laravel-jewellery-showcase

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Run database migrations with seed data
php artisan migrate --seed

# 5. Start dev server (Vite + PHP server)
composer run dev
```

The app is accessible at `http://localhost:8000`.

---

## Media & Storage Configuration

Sonar Haat supports both Cloud Object Storage (S3/R2/MinIO) and local filesystem storage:

### Cloud Storage (AWS S3, Cloudflare R2, MinIO)
Configure bucket credentials in `.env`:
```dotenv
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket_name
AWS_URL=https://your-bucket.s3.amazonaws.com
AWS_USE_PATH_STYLE_ENDPOINT=false
```

Sync sample catalogue images to your bucket:
```bash
php artisan cloud:sync-media --disk=s3
```

### Local Storage
For offline development without S3:
```bash
php artisan storage:link --force
```

---

## Testing & Quality Assurance

```bash
# Run test suite (88 tests / 305 assertions)
composer test

# Run PSR-12 code style check
composer lint

# Automatically format code
composer format

# Run PHPStan Level 5 static analysis
composer analyse

# Run all quality checks (Audit + Lint + Analyse)
composer check
```

---

## Project Structure

```
laravel-jewellery-showcase/
├── app/
│   ├── Enums/                 # ProductStatus, VendorStatus
│   ├── Events/                # Real-time WebSocket events
│   ├── Http/
│   │   ├── Controllers/       # Admin, Vendor, Product, and Reservation controllers
│   │   ├── Middleware/        # Security headers, locale, authorization
│   │   └── Requests/          # Form request validations
│   ├── Models/                # Eloquent models
│   ├── Providers/             # Service providers
│   └── Services/              # ProductSearchService, StockReservationService
├── database/
│   ├── migrations/            # Database schema
│   └── seeders/               # Categories, metals, purities, and sample products
├── resources/
│   ├── views/                 # Blade views (with Dark mode & RTL support)
│   ├── css/                   # Tailwind CSS
│   └── js/                    # Alpine.js and Echo WebSocket setup
├── routes/
│   ├── web.php                # Web routes
│   └── channels.php           # Broadcast channels
└── tests/                     # 88 Feature and Unit tests
```

---

## Security

- **Pessimistic Locking:** `lockForUpdate()` ensures inventory consistency during concurrent reservations.
- **Tenant Isolation:** Policy checks ensure vendors can only access and modify their own products and variants.
- **SKU Management:** Soft-deleting variants frees up the SKU for reuse while maintaining referential integrity.
- **HTTP Headers:** Content-Security-Policy (CSP), HSTS, anti-clickjacking headers, and CSRF protection.

---

## License

This project is open-source software licensed under the [MIT License](LICENSE).
