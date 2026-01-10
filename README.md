# Vending Machine PHP Application

A simple **Vending Machine** system built with **PHP**, **PDO**, and **MySQL**.  
Supports web interface for admins and users, and a **RESTful API** with **JWT authentication**.

---

## Features

### Web Interface
- Admin and User authentication
- Admin CRUD for products and users
- Purchase products with real-time stock update
- Product listing with pagination and sorting
- Validation for all input fields

### REST API
- JWT token-based authentication
- Standardized JSON responses for all endpoints
- CRUD operations for products
- Public endpoints for viewing available products
- Secure API for creating, updating, deleting products

### Design
- Tailwind CSS for clean and responsive UI
- Color scheme:  
  - Primary: Azure Blue (#4863A0)  
  - Secondary: #A08548  
  - Success: #008000  
  - Error: #FF0000

---

## Requirements

- PHP >= 8.2.27
- MySQL >=9.2.0 for macos14.7
- Composer >= 2.7.7
- Web server (Apache/Nginx or built-in PHP server)

---

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/your-username/vending-machine.git
cd vending-machine
```
2. **Install dependencies via Composer**

```bash
composer install
```

3. **Create .env file in project root:**
```bash

DB_HOST=127.0.0.1
DB_NAME=vending_machine
DB_USER=your_db_user_name
DB_PASS=your_db_password

PAGINATION_LIMIT=your_pagination_limit

JWT_SECRET=your_super_secret_key
JWT_EXPIRY=3600
```
4. **Create MySQL database and tables:**

```bash
-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,3) NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Transactions table
CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,3) NOT NULL,
    total_price DECIMAL(10,3) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
```
5. **Start PHP built-in server**
```bash
php -S 127.0.0.1:8000 -t public ./public/router.php
```

### Web Usage
- Admin: Can manage products and users
- User: Can view products and make purchases
- Login page: http://localhost:8000/login
- Products page: http://localhost:8000

### API Usage
#### Base Uri
```bash
http://localhost:8000/api
```

### Authentication
#### POST /api/login
```bash
{
  "email": "admin@example.com",
  "password": "password123"
}
```

#### Use the token in Authorization header for protected endpoints:
```bash
Authorization: Bearer <JWT_TOKEN>
```

### Products API

| Method | Endpoint               | Description         | Auth Required |
| ------ | ---------------------- | ------------------- | ------------- |
| GET    | `/api/products`        | List all products   | No (public)   |
| GET    | `/api/products/{id}`   | Get product details | No (public)   |
| POST   | `/api/products/create` | Create product      | Yes (admin)   |
| PUT    | `/api/products/update` | Update product      | Yes (admin)   |
| DELETE | `/api/products/delete` | Delete product      | Yes (admin)   |

### Standard Json Response
```bash
{
  "status": "success|error",
  "code": 200,
  "message": "Informative message",
  "data": { ... } | null
}
```
