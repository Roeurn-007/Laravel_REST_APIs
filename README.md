# E-Commerce Backend API

A robust Laravel-based REST API for a full-stack e-commerce system with admin panel, user authentication, and comprehensive e-commerce features.

## Features

### Admin Panel (Blade Templates)
- **Dashboard** - Statistics, revenue analytics, order analytics, category sales, customer growth, top products, recent orders, activities, low stock alerts
- **Category Management** - Full CRUD operations (Create, Read, Update, Delete)
- **Product Management** - Full CRUD with image upload support
- **Order Management** - View orders, update order status
- **User Management** - View and manage registered users

### Public API Endpoints (No Authentication Required)
- `GET /api/products` - List all products with filtering, sorting, and pagination
- `GET /api/products/{id}` - Get product details
- `GET /api/products/{product}/reviews` - Get product reviews
- `GET /api/categories` - List all categories
- `GET /api/categories/{id}` - Get category details

### Protected API Endpoints (Laravel Sanctum Authentication)
- **Authentication**
  - `POST /api/register` - User registration
  - `POST /api/login` - User login
  - `POST /api/logout` - User logout
  - `GET /api/profile` - Get user profile

- **Wishlist**
  - `GET /api/wishlist` - View wishlist
  - `POST /api/wishlist` - Add product to wishlist
  - `DELETE /api/wishlist/{product}` - Remove from wishlist

- **Cart**
  - `GET /api/cart` - View cart with totals
  - `POST /api/cart` - Add product to cart
  - `PUT /api/cart/{cartItem}` - Update cart item quantity
  - `DELETE /api/cart/{cartItem}` - Remove cart item

- **Checkout & Orders**
  - `POST /api/checkout` - Checkout cart and create order
  - `GET /api/orders` - View order history (paginated)
  - `GET /api/orders/{order}` - View order details

- **Profile Management**
  - `POST/PUT/PATCH /api/profile` - Update profile (name, email, avatar)
  - `PUT /api/profile/password` - Change password

- **Reviews**
  - `POST /api/products/{product}/reviews` - Write a review (rated 1-5)

## Tech Stack

- **Framework**: Laravel 10+
- **Authentication**: Laravel Sanctum (Token-based API authentication)
- **Database**: MySQL/PostgreSQL/SQLite
- **Template Engine**: Blade (for admin panel)
- **API Resources**: JSON responses with proper HTTP status codes

## Requirements

- PHP 8.1+
- Composer
- Node.js & npm (for asset compilation)
- Database (MySQL/PostgreSQL/SQLite)

## Installation

1. Clone the repository
2. Install PHP dependencies:
   ```bash
   cd backend
   composer install
   ```

3. Copy `.env.example` to `.env` and configure your database:
   ```bash
   cp .env.example .env
   ```

4. Generate application key:
   ```bash
   php artisan key:generate
   ```

5. Run database migrations:
   ```bash
   php artisan migrate
   ```

6. (Optional) Seed the database with sample data:
   ```bash
   php artisan db:seed
   ```

7. Install Laravel Sanctum:
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   php artisan migrate
   ```

8. Create storage link for product images:
   ```bash
   php artisan storage:link
   ```

9. Start the development server:
   ```bash
   php artisan serve
   ```

The API will be available at `http://localhost:8000/api`

## API Usage

### Authentication Flow

1. **Register**:
   ```bash
   POST /api/register
   {
     "name": "John Doe",
     "email": "john@example.com",
     "password": "password123",
     "password_confirmation": "password123"
   }
   ```

2. **Login**:
   ```bash
   POST /api/login
   {
     "email": "john@example.com",
     "password": "password123"
   }
   ```

3. **Use Token**: Include the token in subsequent requests:
   ```
   Authorization: Bearer {token}
   ```

### Example API Calls

**Get Products:**
```bash
GET /api/products?search=headphones&category_id=1&sort=price&direction=asc&per_page=12
```

**Add to Cart:**
```bash
POST /api/cart
Authorization: Bearer {token}

{
  "product_id": 1,
  "quantity": 2
}
```

**Checkout:**
```bash
POST /api/checkout
Authorization: Bearer {token}

{
  "shipping_address": "123 Main St, City, Country",
  "coupon_code": "OFFER25"
}
```

## Database Schema

### Main Tables
- **users** - User accounts (customers & admins)
- **categories** - Product categories
- **products** - Product information with images and stock
- **carts** / **cart_items** - Shopping cart
- **orders** / **order_items** - Order history
- **wishlists** - User wishlists
- **reviews** - Product reviews and ratings

## Admin Credentials

After seeding, you can access the admin panel at:
- URL: `http://localhost:8000/admin/dashboard`
- Email: `admin@example.com`
- Password: `password`

## License

This project is open-source and available under the MIT License.