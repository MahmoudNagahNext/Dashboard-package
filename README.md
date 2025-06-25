# 🚀 NextDashboard Package

**NextDashboard** is a customizable admin dashboard package built specifically for Laravel projects.  
It provides out-of-the-box features including:  
- Admin authentication system  
- Role & permission management using [Spatie Laravel-Permission](https://spatie.be/docs/laravel-permission)  
- A full-featured ticketing system  
- Media management and activity logging via Spatie libraries  

---

## 📦 Installation & Setup

### 1️⃣ Install the Package
```bash
composer require nagahnextdev/nextdashboard:dev-main
```

---

## 🔐 Authentication Setup

Update your `config/auth.php` file:

#### ➕ Add the `admin` guard:
```php
'admin' => [
    'driver' => 'token',
    'provider' => 'admins',
],
```

#### ➕ Add the `admins` provider:
```php
'admins' => [
    'driver' => 'eloquent',
    'model' => \nextdev\nextdashboard\Models\Admin::class,
],
```
---

## 🛡️ Spatie Permission Setup

```bash
php artisan vendor:publish --tag="permission-migrations"
php artisan vendor:publish --tag="permission-config"
php artisan migrate
```

---

## 📁 Media Library Setup

```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-config"
```

Update your `config/filesystems.php`:
```php
'disks' => [
    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],
]
```

Then link the storage:
```bash
php artisan storage:link
```

---

## 📝 Activity Log Setup

```bash
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
php artisan migrate
```

---

## ⚙️ Publish NextDashboard Resources

```bash
php artisan vendor:publish --tag=nextdashboard-migrations
php artisan vendor:publish --tag=nextdashboard-seeders
```

---

## 🌱 Seed Initial Admin User

```bash
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=AdminSeeder
```
> **Note:** Ensure that Spatie permissions are properly installed before running this seeder.

---

## 📢 Available Events

You can list all available events using:
```bash
php artisan nextdashboard:list-events
```

| Event Name             | Description                                       |
|------------------------|---------------------------------------------------|
| `AdminCreated`         | Triggered when a new admin is created             |
| `RoleAssignedToAdmin`  | Triggered when a role is assigned to an admin     |
| `TicketCreated`        | Triggered when a new ticket is created            |
| `TicketAssigned`       | Triggered when a ticket is assigned to an admin   |
| `TicketReplied`        | Triggered when a reply is added to a ticket       |

---

## 🧹 Scheduled Commands

### Clean up expired OTPs:
```bash
php artisan otps:delete-expired
```
> Add this command to the scheduler in your `App\Console\Kernel.php`.