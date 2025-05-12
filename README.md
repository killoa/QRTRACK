# QR Restaurant Menu Tracker

A dynamic restaurant menu system using QR codes. Restaurants can manage their menus, and customers can view randomly selected menu items by scanning QR codes.

## Features

- QR code generation for each restaurant
- Dynamic menu display
- Admin dashboard for restaurant management
- Logo upload functionality
- Random menu selection

## Setup

1. Create a MySQL database and import the `database/schema.sql`
2. Configure database credentials in `config/database.php`
3. Start your PHP server
4. Access the admin dashboard at `/admin/login.php`

## Project Structure

```
/
├── admin/           # Admin dashboard files
├── assets/         # CSS, JS, and images
├── config/         # Configuration files
├── database/       # Database schema and migrations
├── includes/       # PHP includes and functions
├── uploads/        # Uploaded restaurant logos
└── vendor/         # Dependencies
```
