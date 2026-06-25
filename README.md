# Video Store System

The Video Store System is a comprehensive web-based application built with Laravel designed to manage the daily operations of a video rental store. It provides a robust platform for managing movie inventory, tracking rentals, and administering user roles.

## Key Features

- **Inventory Management:** Complete control over Movies, physical Tapes, Actors, and Categories.
- **Rental Management:** Streamlined processes for renting and returning tapes, along with tracking active rentals and rental history.
- **Role-Based Access Control (RBAC):** Fine-grained permission and role management to restrict access based on user types (e.g., Admin, Staff).
- **Audit & Compliance:** Detailed audit logs to track user actions and system changes for security compliance.
- **Reporting & Dashboard:** Administrative dashboards and reporting tools to monitor store performance and track inventory.
- **User Management:** Secure user authentication, registration, and profile management.

## Tech Stack

- **Framework:** [Laravel](https://laravel.com)
- **Language:** PHP
- **Frontend:** Blade Templating, HTML, CSS, JavaScript
- **Database:** Relational Database (MySQL/SQLite)

## Getting Started

1. Clone the repository.
2. Run `composer install` to install PHP dependencies.
3. Run `npm install` and `npm run build` to compile frontend assets.
4. Copy `.env.example` to `.env` and configure your database settings.
5. Run `php artisan key:generate` to generate an application key.
6. Run `php artisan migrate --seed` to run database migrations and seed the database with initial data (including roles and permissions).
7. Run `php artisan serve` to start the local development server.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
