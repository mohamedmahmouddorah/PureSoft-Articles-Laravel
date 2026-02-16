# PureSoft Articles CMS

A simple Article Management System built with Laravel.  
The project focuses on managing articles with a clean and modern interface.

---

## Features

- User authentication (register / login / logout)
- Create, edit, and delete articles
- Comment system for articles
- Responsive design using Bootstrap 5
- Protection against CSRF, XSS, and SQL Injection

---

## Tech Stack

- PHP 8.2+
- Laravel 10 / 11
- MySQL
- Blade
- Bootstrap 5
- Vite

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/your-username/PureSoft-Articles-Laravel.git
cd PureSoft-Articles-Laravel
```

### 2. Install dependencies

```bash
composer install
npm install
npm run build
```

### 3. Setup environment

- Copy `.env.example` to `.env`
- Run:

```bash
php artisan key:generate
```

- Start MySQL from XAMPP
- Create a new database
- Update database credentials inside `.env`

### 4. Run migrations

```bash
php artisan migrate
```

### 5. Start the server

```bash
php artisan serve
```

Open in browser:  
http://127.0.0.1:8000

---

## Author

Mohamed Mahmoud Dorah  
PureSoft Development
