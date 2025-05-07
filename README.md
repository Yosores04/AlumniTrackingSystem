# Alumni Tracking System

<p align="center">
  <img src="public/img/1.svg" alt="Alumni Tracking System Logo" width="200">
</p>

## About The Project

The Alumni Tracking System is a comprehensive web application designed to help educational institutions maintain connections with their graduates, track their professional progress, and foster an engaged alumni community. The system provides robust features for alumni data management, communication, and reporting.

### Key Features

-   **Multi-tenancy Architecture**: Support for multiple departments/institutions with isolated data
-   **Alumni Management**: Track alumni profiles, employment status, and achievements
-   **Instructor Portal**: Dedicated portal for faculty to interact with alumni data
-   **Support Ticket System**: Built-in help desk functionality for users
-   **Customizable Themes**: Branding options for each tenant
-   **Role-based Access Control**: Different permission levels for admins, instructors, and alumni
-   **Version Management**: In-app system updates and rollbacks
-   **Responsive Design**: Mobile-friendly interface for all portals

## Getting Started

### Prerequisites

-   PHP 8.2 or higher
-   MySQL 5.7 or higher
-   Composer
-   Node.js & NPM

### Installation

1. Clone the repository

```bash
git clone https://github.com/GarcianoNilo/WST-T83-ALUMNI-TRACKING-SYSTEM.git
```

2. Install dependencies

```bash
composer install
npm install
```

3. Configure your environment

```bash
cp .env.example .env
php artisan key:generate
```

4. Set up the database

```bash
php artisan migrate
php artisan tenants:migrate
php artisan db:seed
```

5. Run the application

```bash
php artisan serve
composer run dev
npm run build
```

## Developers

This system was developed by:

-   **Joshua James G. Yosores** - Lead Developer
-   **Margaret Zoe A. Neri** - Frontend Developer
-   **Nilo G. Garciano Jr.** - Backend Developer
-   **Sern S. Ponce** - Database Engineer

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Built With

-   [Laravel](https://laravel.com) - The PHP framework used
-   [Tailwind CSS](https://tailwindcss.com) - For responsive UI components
-   [Alpine.js](https://alpinejs.dev) - For lightweight JavaScript interactions
-   [Stancl Tenancy](https://tenancyforlaravel.com) - For multi-tenancy implementation

## Acknowledgements

-   The Laravel Community
-   All the open-source contributors whose packages made this project possible
-   Special thanks to our mentors and reviewers

---

<p align="center"><small>Powered by Laravel</small></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[WebReinvent](https://webreinvent.com/)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Jump24](https://jump24.co.uk)**
-   **[Redberry](https://redberry.international/laravel/)**
-   **[Active Logic](https://activelogic.com)**
-   **[byte5](https://byte5.de)**
-   **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
