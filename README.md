# Mirrel5 CMS

Mirrel5 is a Content Management System (CMS) built on **CodeIgniter 3** with an **Angular 8** admin panel. Designed to create and manage dynamic websites with pages, content, widgets, labels, and a file manager.

## System Requirements

| Component | Version |
|-----------|---------|
| PHP | 8.0+ (patched for PHP 8.x compatibility) |
| MySQL / MariaDB | 5.7+ / 10.1+ |
| Web Server | Apache (XAMPP) |
| Node.js | 10+ (for admin panel development) |

## Project Structure

```
mirrel5/
├── application/            # CodeIgniter application
│   ├── controllers/
│   │   ├── Site.php        # Main frontend controller
│   │   ├── Api.php         # REST API for admin panel
│   │   ├── Login.php       # Admin authentication
│   │   ├── Adminbiz.php    # Admin panel loader
│   │   └── Override.php    # Utilities (sitemap, RSS, URL generator)
│   ├── models/
│   │   ├── Core.php        # Core CMS logic (pages, content, metadata, widgets)
│   │   └── Model.php       # Custom data per theme
│   ├── views/
│   │   ├── themes/         # Frontend templates (home, contact, etc.)
│   │   └── admin/          # Login & admin views
│   └── config/             # CI configuration (database, routes, etc.)
├── admin/                  # Admin panel (Angular 8 build)
│   └── app/                # Production build files
├── dev/                    # Angular admin panel source code
│   └── src/
├── public/                 # Public assets (images, cache, uploads)
├── system/                 # CodeIgniter 3 core (patched for PHP 8.x)
├── assets/                 # Frontend assets (CSS, JS)
└── mirrel5_database.sql    # SQL dump for database setup
```

## Installation

### 1. Clone / Copy Project

Copy the `mirrel5` folder to your web server document root (e.g., `C:\xampp\htdocs\website\mirrel5`).

### 2. Create Database

```sql
CREATE DATABASE mirrel5 CHARACTER SET utf8 COLLATE utf8_general_ci;
```

### 3. Import Database

```bash
mysql -u root mirrel5 < mirrel5_database.sql
```

Or import `mirrel5_database.sql` via phpMyAdmin.

### 4. Configure Database

Edit `application/config/database.php`:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'mirrel5',
    'dbdriver' => 'mysqli',
    // ...
);
```

### 5. Configure Base URL

Edit `application/config/config.php` and set the `base_url`:

```php
$config['base_url'] = 'http://localhost/website/mirrel5/';
```

### 6. Access the Website

- **Frontend**: `http://localhost/website/mirrel5/`
- **Admin Panel**: `http://localhost/website/mirrel5/login`

## Database Tables

| Table | Purpose |
|-------|---------|
| `account` | Admin account data (login, token) |
| `cms_pages` | Website pages (hierarchy, URL, metadata) |
| `cms_content` | Content per page (text, images, metadata) |
| `cms_content_column` | Additional content columns |
| `cms_label` | Static labels/text (footer, header, etc.) |
| `cms_widget` | Widgets (banners, galleries, subcontent) |
| `cms_pages_log` | Page change logs |
| `cms_label_log` | Label change logs |
| `cms_widget_log` | Widget change logs |
| `global_setting` | Global settings (SMTP, etc.) |

## Key Features

- **Pages & Content Management** — Manage pages and content in a hierarchical structure
- **Widget System** — Banners, galleries, subcontent per page
- **Label System** — Static text editable from the admin panel
- **REST API** — JSON API for the Angular admin panel
- **SEO** — Auto-generated meta description, keywords, Open Graph, Twitter Card
- **Sitemap & RSS** — Automatically generated from CMS data
- **Google reCAPTCHA v3** — Contact form protection
- **SMTP Email** — Send emails from the "Contact Us" form
- **File Manager** — Integrated elFinder 2.1
- **HTTPS Support** — Automatic HTTPS redirect (configurable via database)
- **Admin Auth** — Token-based authentication login

## Additional Configuration

### Google reCAPTCHA v3

Edit `application/controllers/Site.php`:

```php
public $site_key = "YOUR_SITE_KEY";
public $secret_key = "YOUR_SECRET_KEY";
```

### SMTP Email

Configure via the `global_setting` table in the database (id 101–105).

### HTTPS

Set `https` to `TRUE` in the database configuration (`application/config/database.php`) to enable HTTPS redirect.

## Admin Panel Development

The `dev/` folder contains the frontend source code for the CMS admin panel, built with **Angular 8** and **TypeScript 3.5**.

### Tech Stack

| Package | Version | Purpose |
|---------|---------|---------|
| Angular | ~8.2.4 | Core framework (core, common, forms, router, animations) |
| TypeScript | ~3.5.3 | Language |
| ng-bootstrap | ^5.1.1 | Bootstrap UI components |
| TinyMCE Angular | ^3.3.1 | WYSIWYG rich text editor |
| RxJS | ~6.4.0 | Reactive programming |
| Karma + Jasmine | ~4.1.0 / ~3.4.0 | Unit testing |
| Protractor | ~5.4.0 | E2E testing |

### App Modules

```
dev/src/app/
├── app.module.ts               # Root module
├── app-routing.module.ts       # Route definitions
├── app.component.*             # Root component
├── home/                       # Dashboard / home screen
├── pages/                      # Pages management (list & edit)
├── content/                    # Content management (list & edit)
├── widget/                     # Widget management (list, edit, section)
├── setting/                    # CMS settings
├── service/                    # API services (HTTP calls to backend)
├── guard/                      # Route guards (authentication)
└── page-not-found-component/   # 404 page
```

### Routes

| Route | Component | Description |
|-------|-----------|-------------|
| `/` | HomeComponent | Dashboard |
| `/setting` | SettingComponent | CMS settings (guarded) |
| `/pages` | PagesComponent | Page list (guarded) |
| `/pages/:id` | PagesComponent | Page list filtered by parent |
| `/pages/edit/:id` | PagesEditComponent | Edit a page |
| `/content` | ContentComponent | Content list (guarded) |
| `/content/:id` | ContentEditComponent | Edit content |
| `/widget` | WidgetComponent | Widget list (guarded) |
| `/widget/section/:section` | WidgetSectionComponent | Widgets by section |
| `/widget/:id` | WidgetEditComponent | Edit a widget |
| `**` | PageNotFoundComponent | 404 fallback |

All admin routes (except `/`) are protected by `ActiveGuardGuard` (token-based auth). Routing uses hash strategy (`useHash: true`).

### Build Configuration

Defined in `dev/angular.json`:

- **Output path**: `../admin/app` (builds directly into the `admin/app/` folder)
- **Source root**: `src/`
- **Entry point**: `src/main.ts`
- **Styles**: `src/styles.css`
- **AOT**: Disabled (both dev and production)
- **Production optimizations**: Minification enabled, source maps disabled, output hashing enabled
- **Budget limits**: 2MB warning / 5MB error for initial bundle

### Development Commands

```bash
cd dev
npm install             # Install dependencies
ng serve                # Start dev server (default: http://localhost:4200)
ng build --prod         # Production build → outputs to ../admin/app/
ng test                 # Run unit tests (Karma + Jasmine)
ng e2e                  # Run E2E tests (Protractor)
ng lint                 # Lint TypeScript code
```

### Environment Files

- `src/environments/environment.ts` — Development config
- `src/environments/environment.prod.ts` — Production config (auto-swapped during `--prod` build)

## PHP 8.x Compatibility Notes

This project has been patched for PHP 8.x compatibility. Changes made to the CodeIgniter 3 core:

- `#[\AllowDynamicProperties]` added to `CI_Controller`, `CI_Model`, `CI_URI`, `CI_Router`, `CI_Loader`, `CI_DB_driver`, `CI_Driver_Library`
- `#[\ReturnTypeWillChange]` added to all session driver methods
- Null coalescing (`?? ''`) applied to `str_replace()`, `strip_tags()`, `preg_replace()`, `strtotime()`, `strpos()` that receive null parameters

## License

CodeIgniter 3 is licensed under the [MIT License](https://opensource.org/licenses/MIT).