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

### 5. Spatie Media
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-config"


in config/filesystems.php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
]

run 
php artisan storage:link

### 6. spatie permission
php artisan vendor:publish --tag="permission-migrations"
php artisan vendor:publish --tag="permission-config"
php artisan migrate

### 7. Spatia Laravel-activitylog
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
php artisan migrate

## 📢 Available Events

you can list all events using this command
-- php artisan nextdashboard:list-events

The following events are dispatched by the `nextdashboard` package:

| Event Name               | Description                                          |
|--------------------------|------------------------------------------------------|
| `AdminCreated`           | Dispatched when a new admin is created.              |
| `RoleAssignedToAdmin`    | Dispatched when a role is assigned to an admin.      |
| `TicketCreated`          | Dispatched when a new ticket is created.             |
| `TicketAssigned`         | Dispatched when a ticket is assigned to an admin.    |
| `TicketReplied`          | Dispatched when a reply is added to a ticket.        |
