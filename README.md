# E-Commerce REST API with Laravel Passport

A complete, production-ready REST API for an e-commerce application built with Laravel 11 and Laravel Passport for OAuth2 authentication.

## Features

✨ **Complete E-Commerce Functionality**
- 🔐 Full authentication system (Register, Login, Logout, Password Reset)
- 👤 User profile management with image upload
- 📦 Product catalog with categories and search
- 🛒 Shopping cart system
- 📝 Order management and tracking
- ⭐ Product reviews and ratings
- ❤️ Favorites/Wishlist
- 📍 Multiple delivery addresses
- 💳 Payment processing (COD, Bank Transfer, E-wallet)
- 🔔 Push notifications
- 🎨 Banner management

## Tech Stack

- **Framework**: Laravel 11
- **Authentication**: Laravel Passport (OAuth2)
- **Database**: MySQL/PostgreSQL/SQLite
- **PHP Version**: 8.2+

## Quick Start

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL/PostgreSQL/SQLite
- Git

### Installation

1. **Clone and install dependencies**
```bash
git clone <repository-url>
cd restapi-kede
composer install
```

2. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configure database in `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restapi_kede
DB_USERNAME=root
DB_PASSWORD=your_password
```

4. **Run setup script**

**Windows:**
```bash
setup.bat
```

**Linux/Mac:**
```bash
chmod +x setup.sh
./setup.sh
```

Or manually:
```bash
php artisan migrate
php artisan passport:install
php artisan storage:link
```

5. **Seed demo data (optional)**
```bash
php artisan db:seed --class=DemoDataSeeder
```

6. **Start development server**
```bash
php artisan serve
```

API will be available at: `http://localhost:8000/api`

## Testing the API

### Option 1: Using Postman

1. Import `postman_collection.json` into Postman
2. The collection includes all API endpoints with examples
3. Use the Login endpoint to get an access token
4. Token will be automatically saved for authenticated requests

### Option 2: Using cURL

**Register a new user:**
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "08123456789"
  }'
```

**Login:**
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

**Use the returned access token for authenticated requests:**
```bash
curl -X GET http://localhost:8000/api/users/profile \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### Demo User Credentials (if seeded)

- Email: `demo@example.com`
- Password: `password`

## API Documentation

Complete API documentation is available in [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

### Quick Overview

**Authentication Endpoints:**
- POST `/api/auth/register` - Register new user
- POST `/api/auth/login` - Login user
- POST `/api/auth/logout` - Logout user
- POST `/api/auth/refresh-token` - Refresh access token
- POST `/api/auth/forgot-password` - Request password reset
- POST `/api/auth/reset-password` - Reset password

**Main Resource Endpoints:**
- `/api/categories` - Category management
- `/api/products` - Product catalog
- `/api/cart` - Shopping cart
- `/api/addresses` - Delivery addresses
- `/api/orders` - Order management
- `/api/reviews` - Product reviews
- `/api/favorites` - User favorites
- `/api/banners` - Banner ads
- `/api/notifications` - User notifications
- `/api/payments` - Payment processing

## Database Schema

The API includes the following main tables:

- **users** - User accounts
- **categories** - Product categories
- **products** - Product catalog
- **addresses** - Delivery addresses
- **carts** / **cart_items** - Shopping cart
- **orders** / **order_items** - Orders
- **reviews** - Product reviews
- **banners** - Promotional banners
- **favorites** - User wishlists
- **notifications** - User notifications
- **oauth_*** - Laravel Passport tables

## Project Structure

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           ├── AuthController.php
│           ├── UserController.php
│           ├── CategoryController.php
│           ├── ProductController.php
│           ├── CartController.php
│           ├── AddressController.php
│           ├── OrderController.php
│           ├── ReviewController.php
│           ├── FavoriteController.php
│           ├── BannerController.php
│           ├── NotificationController.php
│           └── PaymentController.php
├── Models/
│   ├── User.php
│   ├── Category.php
│   ├── Product.php
│   ├── Address.php
│   ├── Cart.php
│   ├── CartItem.php
│   ├── Order.php
│   ├── OrderItem.php
│   ├── Review.php
│   ├── Banner.php
│   ├── Favorite.php
│   └── Notification.php
└── Traits/
    └── ApiResponse.php

database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   ├── 2024_12_12_000001_create_categories_table.php
│   ├── 2024_12_12_000002_create_products_table.php
│   └── ... (all migration files)
└── seeders/
    └── DemoDataSeeder.php

routes/
└── api.php
```

## Response Format

All API responses follow a consistent format:

**Success:**
```json
{
  "success": true,
  "message": "Success message",
  "data": { }
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error message",
  "errors": []
}
```

**Paginated:**
```json
{
  "success": true,
  "data": [],
  "pagination": {
    "page": 1,
    "limit": 10,
    "total": 100,
    "totalPages": 10
  }
}
```

## Authentication

This API uses **Laravel Passport** for OAuth2 authentication with Bearer tokens.

Include the access token in all authenticated requests:
```
Authorization: Bearer YOUR_ACCESS_TOKEN
```

Token Configuration:
- Access tokens expire in 15 days
- Refresh tokens expire in 30 days

## Security Features

- ✅ Password hashing with bcrypt
- ✅ OAuth2 authentication via Passport
- ✅ Token expiration management
- ✅ CSRF protection
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ Input validation on all endpoints
- ✅ Mass assignment protection

## Development Notes

- All controllers use the `ApiResponse` trait for consistent responses
- Models include proper relationships and type casting
- Automatic stock management on order creation/cancellation
- Automatic rating updates when reviews change
- Automatic cart total calculation

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues, questions, or contributions, please open an issue in the repository.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
