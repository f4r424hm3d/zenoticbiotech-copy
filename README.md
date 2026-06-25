# Zenotic Biotech Laravel App

This app renders the public website and admin catalog dashboard with Laravel Blade.

## Local Setup

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve --host=127.0.0.1 --port=5000
```

Public site: `http://127.0.0.1:5000`

Admin: `http://127.0.0.1:5000/admin/login`

Seeded admin credentials from `.env`:

- Email: `farazahmad280@gmail.com`
- Password: `F4r424hm3d@`

Public API endpoints remain available under `/api`.
