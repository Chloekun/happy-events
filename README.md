# Happy Events

Happy Events is a PHP-based event booking and management system built on a simple MVC-style structure.

## Overview

This application provides a customer-facing booking experience plus an admin panel for managing bookings, services, quotations, customers, and reports.

## Key Features

- User authentication
  - Login
  - Registration
  - Logout
- Client features
  - Browse services
  - Submit bookings
  - Request quotations
  - View profile and dashboard
- Admin features
  - Dashboard overview
  - Manage bookings
  - Manage customers
  - Manage services
  - View reports
- Support pages
  - Home
  - About
  - Contact
  - Gallery

## Architecture

- `index.php` - main entry point and simple router
- `config/` - application settings and database configuration
- `controllers/` - request handling and business logic
- `models/` - data models and database interactions
- `views/` - HTML templates organized by client/admin views
- `assets/` - CSS and JavaScript assets
- `storage/` - runtime files such as logs and temporary storage

## Important Files

- `config/config.php` - site settings and environment configuration
- `config/database.php` - database connection settings
- `controllers/AuthController.php` - handles login, registration, forgot password, and logout
- `controllers/HomeController.php` - handles public pages and client dashboard
- `controllers/AdminController.php` - handles admin dashboard and management pages
- `controllers/BookingController.php` - handles booking operations
- `controllers/QuotationController.php` - handles quotation requests
- `models/User.php` - user authentication and registration logic

## Usage

1. Place the project in your web server document root (for example, `C:\xampp\htdocs\happy-events`).
2. Configure your database connection in `config/database.php`.
3. Open the browser at `http://localhost/happy-events/`.
4. Use the root-level pages for authentication:
   - `login.php`
   - `register.php`

## Notes

- The app uses a simple controller routing style: `index.php?controller=<name>&action=<name>`.
- Admin and client views are separated under `views/admin/` and `views/client/`.

## Contact

For support or customization, contact the site administrator at the configured admin email.
