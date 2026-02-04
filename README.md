# Multi-Vendor E-Commerce Platform

A multi-vendor e-commerce web application built with **Laravel**, **Inertia.js**, **Vue 3**, and **Tailwind CSS**.

This project is created as a **portfolio project** to practice real-world Laravel architecture, role-based access control, and modern SPA development.

## Features

### Authentication & UI
- User authentication
- Dark / light mode support
- Responsive UI using Tailwind CSS
- SPA experience powered by Inertia.js

### Roles & Permissions
- **Admin**
  - Manage users
  - Manage categories
  - Manage products
  - View and manage all orders
- **Vendor**
  - Manage own products
  - View vendor-specific orders
  - Update order status
- **Customer**
  - Browse products
  - Add items to cart
  - Checkout and place orders
  - View order history

### Access Control & Policies
- Product actions are protected by `ProductPolicy`:
  - Admin can manage all products
  - Vendor can manage only their own products
- Unit tests ensure policies behave correctly

## Tech Stack

- **Backend:** Laravel
- **Frontend:** Vue 3, Inertia.js
- **Styling:** Tailwind CSS
- **Authentication:** Laravel Fortify
- **Database:** MySQL / SQLite
- **Build Tool:** Vite
- **Deployment:** Railway

## Project Structure (Simplified)
```
app/
└── Http/
├── Controllers/
├── Middleware/
└── Requests/ (planned)
resources/
└── js/
├── Pages/
├── Components/
└── Layouts/
routes/
├── web.php
└── settings.php
```
## Local Setup

```
git clone https://github.com/your-username/your-repo-name.git
cd your-repo-name

composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate
npm run dev
php artisan serve
```

## Future Improvements
- Future Improvements
- Form Request validation
- Order status history
- Admin dashboard analytics
- Automated tests

## Project Purpose
This project is built to:
- Practice real-world Laravel + Vue architecture
- Implement role-based systems
- Serve as a long-term portfolio project
