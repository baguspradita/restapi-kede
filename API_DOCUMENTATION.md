# E-Commerce REST API - Laravel Passport

This is a complete REST API for an e-commerce application built with Laravel 11 and Passport authentication.

## Features

- Complete authentication system (Register, Login, Logout, Password Reset)
- User profile management
- Product catalog with categories
- Shopping cart functionality
- Order management
- Review and rating system
- Favorites/Wishlist
- Multiple delivery addresses
- Payment processing
- Push notifications
- Banner management

## Installation

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL or PostgreSQL
- Node.js & NPM (for frontend assets if needed)

### Setup Steps

1. **Clone the repository and install dependencies**
```bash
composer install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configure Database**
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restapi_kede
DB_USERNAME=root
DB_PASSWORD=your_password
```

4. **Run Migrations**
```bash
php artisan migrate
```

5. **Install and Configure Passport**
```bash
php artisan passport:install
```

This will create encryption keys and OAuth clients. Save the client ID and secret.

6. **Create Storage Link**
```bash
php artisan storage:link
```

7. **Start Development Server**
```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api`

## API Documentation

### Authentication Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | /api/auth/register | Register new user | No |
| POST | /api/auth/login | Login user | No |
| POST | /api/auth/logout | Logout user | Yes |
| POST | /api/auth/refresh-token | Refresh access token | Yes |
| POST | /api/auth/forgot-password | Request password reset | No |
| POST | /api/auth/reset-password | Reset password | No |

### User Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /api/users/profile | Get user profile | Yes |
| PUT | /api/users/profile | Update user profile | Yes |
| PUT | /api/users/password | Update password | Yes |
| POST | /api/users/profile-image | Upload profile image | Yes |

### Category Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /api/categories | Get all categories | No |
| GET | /api/categories/:id | Get category by ID | No |
| GET | /api/categories/:id/products | Get products in category | No |

### Product Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /api/products | Get all products (with filters) | No |
| GET | /api/products/:id | Get product details | No |
| GET | /api/products/search | Search products | No |
| GET | /api/products/popular | Get popular products | No |
| GET | /api/products/deals | Get discounted products | No |

### Cart Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /api/cart | Get user's cart | Yes |
| POST | /api/cart/items | Add item to cart | Yes |
| PUT | /api/cart/items/:id | Update cart item quantity | Yes |
| DELETE | /api/cart/items/:id | Remove item from cart | Yes |
| DELETE | /api/cart | Clear cart | Yes |

### Address Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /api/addresses | Get all addresses | Yes |
| GET | /api/addresses/:id | Get address by ID | Yes |
| POST | /api/addresses | Create new address | Yes |
| PUT | /api/addresses/:id | Update address | Yes |
| DELETE | /api/addresses/:id | Delete address | Yes |
| PUT | /api/addresses/:id/default | Set default address | Yes |

### Order Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /api/orders | Get order history | Yes |
| GET | /api/orders/:id | Get order details | Yes |
| POST | /api/orders | Create new order (checkout) | Yes |
| PUT | /api/orders/:id/cancel | Cancel order | Yes |
| GET | /api/orders/:id/track | Track order status | Yes |

### Review Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /api/products/:id/reviews | Get product reviews | No |
| POST | /api/orders/:orderId/reviews | Create review | Yes |
| PUT | /api/reviews/:id | Update review | Yes |
| DELETE | /api/reviews/:id | Delete review | Yes |

### Favorite Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /api/favorites | Get favorite products | Yes |
| POST | /api/favorites | Add to favorites | Yes |
| DELETE | /api/favorites/:productId | Remove from favorites | Yes |

### Banner Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /api/banners | Get all active banners | No |

### Notification Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | /api/notifications | Get user notifications | Yes |
| PUT | /api/notifications/:id/read | Mark as read | Yes |
| PUT | /api/notifications/read-all | Mark all as read | Yes |
| DELETE | /api/notifications/:id | Delete notification | Yes |

### Payment Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | /api/payments/process | Process payment | Yes |
| GET | /api/payments/:id/status | Check payment status | Yes |
| POST | /api/payments/webhook | Payment gateway webhook | No |

## Query Parameters

### Products/Categories
- `page` - Page number (default: 1)
- `limit` - Items per page (default: 10)
- `sort` - Sort by: price_asc, price_desc, popular, newest
- `minPrice` - Minimum price filter
- `maxPrice` - Maximum price filter
- `category` - Filter by category ID
- `search` - Search keyword

### Orders
- `page` - Page number
- `limit` - Items per page
- `status` - Filter by status (pending, processing, shipped, delivered, cancelled)
- `startDate` - Start date (YYYY-MM-DD)
- `endDate` - End date (YYYY-MM-DD)

## Response Format

### Success Response
```json
{
  "success": true,
  "message": "Success message",
  "data": {}
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "errors": []
}
```

### Paginated Response
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

This API uses Laravel Passport for OAuth2 authentication. Include the access token in the Authorization header:

```
Authorization: Bearer YOUR_ACCESS_TOKEN
```

### Example: Register & Login

**Register:**
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

**Use Token:**
```bash
curl -X GET http://localhost:8000/api/users/profile \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

## Database Schema

### Tables Created:
- users
- categories
- products
- addresses
- carts
- cart_items
- orders
- order_items
- reviews
- banners
- favorites
- notifications
- password_reset_tokens
- oauth_access_tokens (Passport)
- oauth_clients (Passport)
- oauth_refresh_tokens (Passport)

## Testing

You can test the API using:
- Postman
- Insomnia
- cURL
- HTTPie

Import the endpoints into your API testing tool or use the examples provided above.

## Status Codes

- 200 - Success
- 201 - Created
- 400 - Bad Request
- 401 - Unauthorized
- 403 - Forbidden
- 404 - Not Found
- 422 - Validation Error
- 500 - Internal Server Error

## Security Features

- Password hashing with bcrypt
- OAuth2 authentication via Passport
- Token expiration (15 days for access tokens, 30 days for refresh tokens)
- CSRF protection
- SQL injection prevention via Eloquent ORM
- Input validation on all endpoints

## Development Notes

- All controllers use the `ApiResponse` trait for consistent response formatting
- Models include proper relationships and casts
- Migrations are timestamped for proper ordering
- Product stock is automatically updated when orders are placed or cancelled
- Product ratings are automatically recalculated when reviews are added/updated/deleted
- Cart totals are automatically updated when items change

## License

This project is open-sourced software licensed under the MIT license.
