# Quick Reference Guide - E-Commerce REST API

## Quick Commands

```bash
# Setup
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan passport:install
php artisan storage:link

# Seed demo data
php artisan db:seed --class=DemoDataSeeder

# Start server
php artisan serve

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Run migrations
php artisan migrate:fresh --seed
```

## Most Used Endpoints

### Authentication
```bash
# Register
POST /api/auth/register
Body: { name, email, password, password_confirmation, phone }

# Login
POST /api/auth/login
Body: { email, password }
Returns: { user, access_token, token_type }

# Logout
POST /api/auth/logout
Headers: Authorization: Bearer {token}
```

### Products
```bash
# Get all products
GET /api/products?page=1&limit=10&sort=newest

# Get product details
GET /api/products/{id}

# Search products
GET /api/products/search?search=keyword

# Popular products
GET /api/products/popular

# Products with deals
GET /api/products/deals
```

### Cart
```bash
# Get cart
GET /api/cart
Headers: Authorization: Bearer {token}

# Add to cart
POST /api/cart/items
Body: { product_id, quantity }
Headers: Authorization: Bearer {token}

# Update cart item
PUT /api/cart/items/{id}
Body: { quantity }
Headers: Authorization: Bearer {token}

# Remove from cart
DELETE /api/cart/items/{id}
Headers: Authorization: Bearer {token}
```

### Orders
```bash
# Create order (checkout)
POST /api/orders
Body: {
  address_id,
  items: [{ product_id, quantity }],
  payment_method,
  notes
}
Headers: Authorization: Bearer {token}

# Get orders
GET /api/orders?status=pending&page=1
Headers: Authorization: Bearer {token}

# Get order details
GET /api/orders/{id}
Headers: Authorization: Bearer {token}

# Cancel order
PUT /api/orders/{id}/cancel
Headers: Authorization: Bearer {token}

# Track order
GET /api/orders/{id}/track
Headers: Authorization: Bearer {token}
```

### Addresses
```bash
# Get all addresses
GET /api/addresses
Headers: Authorization: Bearer {token}

# Create address
POST /api/addresses
Body: {
  label, recipient_name, phone, full_address,
  city, province, postal_code, is_default
}
Headers: Authorization: Bearer {token}

# Set default address
PUT /api/addresses/{id}/default
Headers: Authorization: Bearer {token}
```

### Reviews
```bash
# Get product reviews
GET /api/products/{id}/reviews

# Create review
POST /api/orders/{orderId}/reviews
Body: { product_id, rating, comment, images }
Headers: Authorization: Bearer {token}

# Update review
PUT /api/reviews/{id}
Body: { rating, comment }
Headers: Authorization: Bearer {token}
```

### Favorites
```bash
# Get favorites
GET /api/favorites
Headers: Authorization: Bearer {token}

# Add to favorites
POST /api/favorites
Body: { product_id }
Headers: Authorization: Bearer {token}

# Remove from favorites
DELETE /api/favorites/{productId}
Headers: Authorization: Bearer {token}
```

## Query Parameters

### Products & Categories
- `page` - Page number (default: 1)
- `limit` - Items per page (default: 10)
- `sort` - Sort by: `price_asc`, `price_desc`, `popular`, `newest`
- `minPrice` - Minimum price filter
- `maxPrice` - Maximum price filter
- `category` - Filter by category ID
- `search` - Search keyword

### Orders
- `page` - Page number
- `limit` - Items per page
- `status` - Filter by status: `pending`, `processing`, `shipped`, `delivered`, `cancelled`
- `startDate` - Start date (YYYY-MM-DD)
- `endDate` - End date (YYYY-MM-DD)

## Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized (missing/invalid token)
- `403` - Forbidden (no permission)
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Common Errors

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthorized",
  "errors": []
}
```
**Solution**: Include valid Bearer token in Authorization header

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": ["The email field is required."]
  }
}
```
**Solution**: Check request body fields

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found",
  "errors": []
}
```
**Solution**: Check if resource ID exists

## Demo User Credentials

After running the seeder:
- **Email**: demo@example.com
- **Password**: password

## Testing Flow

1. **Register/Login** → Get access token
2. **Browse products** → GET /api/products
3. **Add to cart** → POST /api/cart/items
4. **Create address** → POST /api/addresses
5. **Checkout** → POST /api/orders
6. **Track order** → GET /api/orders/{id}/track
7. **Leave review** → POST /api/orders/{orderId}/reviews

## Environment Variables

Key variables in `.env`:

```env
# Application
APP_NAME="E-Commerce API"
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restapi_kede
DB_USERNAME=root
DB_PASSWORD=

# Mail (for password reset)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

## Useful Artisan Commands

```bash
# Check routes
php artisan route:list

# Database
php artisan migrate:fresh
php artisan migrate:rollback
php artisan db:seed

# Passport
php artisan passport:install
php artisan passport:keys
php artisan passport:client --personal

# Cache
php artisan optimize
php artisan optimize:clear
```

## File Upload Locations

- Profile images: `storage/app/public/profile-images/`
- Review images: `storage/app/public/review-images/`

Access via: `http://localhost:8000/storage/filename.jpg`

## Database Relationships

```
User → Addresses (1:N)
User → Cart (1:1)
User → Orders (1:N)
User → Favorites (1:N)
User → Reviews (1:N)

Category → Products (1:N)

Product → CartItems (1:N)
Product → OrderItems (1:N)
Product → Reviews (1:N)
Product → Favorites (1:N)

Cart → CartItems (1:N)

Order → OrderItems (1:N)
Order → Reviews (1:N)
```

## Tips

1. **Always include Authorization header** for protected routes
2. **Token expires in 15 days** - use refresh token endpoint
3. **Images are stored in storage/app/public** - run `php artisan storage:link`
4. **Check logs** at `storage/logs/laravel.log` for errors
5. **Use Postman collection** for easy testing
6. **Product ratings** update automatically when reviews change
7. **Stock levels** update automatically on order creation/cancellation

## Need Help?

- Check [API_DOCUMENTATION.md](API_DOCUMENTATION.md) for detailed documentation
- Check [README.md](README.md) for setup instructions
- Review Laravel documentation: https://laravel.com/docs
- Review Passport documentation: https://laravel.com/docs/passport
