# Sonar Haat — Multi-Vendor Jewellery Showcase

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)

A multi-vendor jewellery showcase application built with Laravel 12, Alpine.js, and Tailwind CSS. Features vendor catalog management, multi-attribute filtering, real-time stock reservations with WebSocket broadcasts, multi-angle studio photography, and bulk CSV imports.

---

## Features

- **Vendor Portal:** Manage products, multi-angle images, metal types, purities, sizes, and stone settings.
- **Admin Oversight:** Approval and suspension workflows with automatic cascade updates.
- **Search & Filtering:** Faceted filters for metals, gemstones, purities, categories, and price ranges.
- **Real-Time Stock Reservations:** Concurrency control with temporary 15-minute customer holds.
- **Bulk CSV Import:** Chunked queue processing with row validation.

---

## Tech Stack

- **Backend:** Laravel 12 (PHP 8.3+)
- **Database & Cache:** MySQL 8.0+ / SQLite, Redis
- **WebSockets:** Laravel Reverb
- **Frontend:** Blade, Alpine.js, Tailwind CSS

---

## Basic Installation

```bash
git clone https://github.com/i-priyanshuverma/laravel-jewellery-showcase.git
cd laravel-jewellery-showcase
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

---

## License

This project is open-source software licensed under the [MIT License](LICENSE).
