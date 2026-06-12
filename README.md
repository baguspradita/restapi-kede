# Kede REST API

A RESTful API backend for e-commerce applications built with Laravel 12 and Laravel Passport (OAuth2).

## Features

- Authentication system (Register, Login, Logout, Password Reset)
- User profile management with image upload
- Product catalog with categories, search, and filtering
- Shopping cart management
- Order management and tracking
- Product reviews and ratings
- Favorites / Wishlist
- Multiple delivery addresses per user
- Payment processing (COD, Bank Transfer, E-wallet)
- Push notifications
- Banner management

## Tech Stack

| Technology | Description |
|---|---|
| Laravel 12 | PHP framework |
| PHP 8.2+ | Runtime |
| Laravel Passport | OAuth2 authentication |
| SQLite / MySQL | Database |
| Pest PHP | Testing |
| Vite | Frontend build tool |

## Quick Start

### Prerequisites

- PHP >= 8.2
- Composer
- SQLite or MySQL

### Installation

```bash
git clone <repository-url>
cd restapi-kede
composer install
```

```bash
cp .env.example .env
php artisan key:generate
```

Configure your database in `.env`. For MySQL:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restapi_kede
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations, Passport installation, and seed demo data:

```bash
php artisan migrate
php artisan passport:install
php artisan db:seed
```

Create the storage symlink:

```bash
php artisan storage:link
```

Start the development server:

```bash
php artisan serve
```

The API is now available at `http://localhost:8000/api`.

### Demo Credentials

| Email | Password |
|---|---|
| admin@kede.com | password |
| test@kede.com | test123 |

## API Overview

### Public Endpoints

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/categories` | List all categories |
| GET | `/api/categories/{id}` | Get category details |
| GET | `/api/categories/{id}/products` | Get products by category |
| GET | `/api/products` | List products (filterable, paginated) |
| GET | `/api/products/search` | Search products |
| GET | `/api/products/popular` | Popular products |
| GET | `/api/products/deals` | Discounted products |
| GET | `/api/products/{id}` | Get product details |
| GET | `/api/products/{id}/reviews` | Get product reviews |
| GET | `/api/banners` | List active banners |

### Authentication Endpoints

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/auth/register` | Register new user |
| POST | `/api/auth/login` | Login |
| POST | `/api/auth/logout` | Logout (authenticated) |
| POST | `/api/auth/refresh-token` | Refresh access token |
| POST | `/api/auth/forgot-password` | Request password reset |
| POST | `/api/auth/reset-password` | Reset password |

### Authenticated Endpoints

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/users/profile` | Get user profile |
| PUT | `/api/users/profile` | Update profile |
| PUT | `/api/users/password` | Change password |
| POST | `/api/users/profile-image` | Upload profile image |
| GET | `/api/addresses` | List addresses |
| POST | `/api/addresses` | Create address |
| PUT | `/api/addresses/{id}` | Update address |
| DELETE | `/api/addresses/{id}` | Delete address |
| PUT | `/api/addresses/{id}/default` | Set default address |
| GET | `/api/cart` | Get cart contents |
| POST | `/api/cart/items` | Add item to cart |
| PUT | `/api/cart/items/{id}` | Update cart item |
| DELETE | `/api/cart/items/{id}` | Remove cart item |
| DELETE | `/api/cart` | Clear cart |
| GET | `/api/orders` | List orders |
| POST | `/api/orders` | Create order (checkout) |
| GET | `/api/orders/{id}` | Get order details |
| PUT | `/api/orders/{id}/cancel` | Cancel order |
| GET | `/api/orders/{id}/track` | Track order status |
| POST | `/api/orders/{orderId}/reviews` | Submit review |
| GET | `/api/favorites` | List favorites |
| POST | `/api/favorites` | Add to favorites |
| DELETE | `/api/favorites/{productId}` | Remove from favorites |
| GET | `/api/notifications` | List notifications |
| PUT | `/api/notifications/{id}/read` | Mark as read |
| PUT | `/api/notifications/read-all` | Mark all as read |
| DELETE | `/api/notifications/{id}` | Delete notification |
| POST | `/api/payments/process` | Process payment |

## Response Format

All API responses follow a consistent structure:

**Success:**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {}
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error description",
  "errors": {}
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
    "total": 50,
    "totalPages": 5
  }
}
```

## Authentication

This API uses OAuth2 via Laravel Passport. Include the access token in all authenticated requests:

```
Authorization: Bearer YOUR_ACCESS_TOKEN
```

- Access tokens expire after 15 days
- Refresh tokens expire after 30 days

## Project Structure

```
app/
├── Http/Controllers/Api/     # 12 API controllers
├── Models/                    # 11 Eloquent models
└── Traits/ApiResponse.php     # Consistent response format

database/
├── migrations/                # 22 migration files
└── seeders/                   # Demo data seeders

routes/
└── api.php                    # API route definitions

storage/app/public/            # File uploads (profile images, reviews)
```

## Database Schema

The API manages the following entities:

- **Users** - Account management with profile images
- **Categories** - Product categorization (hierarchical)
- **Products** - Product catalog with pricing, stock, images
- **Addresses** - Multiple delivery addresses per user
- **Carts & Cart Items** - Shopping cart with auto-calculated totals
- **Orders & Order Items** - Order management with status tracking
- **Reviews** - Product reviews with ratings and images
- **Banners** - Promotional banners
- **Favorites** - User wishlist
- **Notifications** - User notifications
- **OAuth tables** - Passport authentication tables

## Testing

```bash
php artisan test
```

## Documentation

- `API_DOCUMENTATION.md` - Complete API reference
- `QUICK_REFERENCE.md` - Quick command reference
- `postman_collection.json` - Ready-to-import Postman collection

## License

This project is licensed under the MIT License.
