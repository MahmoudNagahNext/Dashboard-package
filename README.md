# NextDashboard Package

A customizable admin dashboard package built for Laravel projects. 
This package provides ready-to-use migrations, seeders, and authentication setup for admin users.
And Ticking system
---

## Installation

### 1. Require the package via Composer
composer require nagahnextdev/nextdashboard:dev-main


### 2. Publish package resources
php artisan vendor:publish --tag=nextdashboard-migrations
php artisan vendor:publish --tag=nextdashboard-seeders

### 3. Run the seeders
php artisan db:seed --class=TicketPrioritySeeder
php artisan db:seed --class=TicketStatusSeeder

### 4. Authentication Setup
Update your config/auth.php file to support the admin guard used by the package.

Add the admin guard
'admin' => [
    'driver' => 'token',
    'provider' => 'admins',
],

Add the admins provider
'admins' => [
    'driver' => 'eloquent',
    'model' => \nextdev\nextdashboard\Models\Admin::class,
],

Add password reset configuration for admins
'admins' => [
    'provider' => 'admins',
    'table' => 'password_reset_tokens',
    'expire' => 60,
],
