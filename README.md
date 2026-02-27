# PHP_Laravel12_Role_Based_Route_Protection

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue" alt="PHP Version">
  <img src="https://img.shields.io/badge/Auth-Breeze-green" alt="Authentication">
  <img src="https://img.shields.io/badge/Role-Based%20Access-Control-orange" alt="RBAC">
  <img src="https://img.shields.io/badge/License-MIT-lightgrey" alt="License">
</p>


##  Overview

This project demonstrates how to implement **Role-Based Route Protection** in a Laravel 12 application. It includes authentication, user role management, middleware-based access control, and protected routes for different user roles such as **Admin** and **Customer**.

The system ensures that users can only access the parts of the application that match their assigned role.

---

##  Features

* Laravel 12 authentication using Breeze
* Role column added to users table
* Custom Role Middleware
* Middleware alias registration using Laravel 12 structure
* Admin-only and Customer-only route protection
* Proper 403 Unauthorized handling

---

##  Folder Structure

```
app/
 ├── Http/
 │    ├── Controllers/
 │    │     ├── AdminController.php
 │    │     └── CustomerController.php
 │    └── Middleware/
 │          └── RoleMiddleware.php
 │
 ├── Models/
 │    └── User.php
 │
bootstrap/
 └── app.php

routes/
 └── web.php
```

---

##  Step 1: Install Laravel 12

```bash
composer create-project laravel/laravel role-based-auth
```

### .ENV File Configuration

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=role
DB_USERNAME=root
DB_PASSWORD=
```

---

##  Step 2: Install Authentication (Laravel Breeze)

```bash
composer require laravel/breeze --dev

php artisan breeze:install

npm install && npm run build

php artisan migrate
```

---

##  Step 3: Add Role Column to Users Table

```bash
php artisan make:migration add_role_to_users_table
```

### Migration File

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
```

```bash
php artisan migrate
```

---

##  Step 4: Update User Model

**app/Models/User.php**

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role',
];
```

---

##  Step 5: Assign Default Role on Registration

**app/Http/Controllers/Auth/RegisteredUserController.php**

```php
$user = User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => Hash::make($request->password),
    'role' => 'customer',
]);
```

---

##  Step 6: Create Role Middleware

```bash
php artisan make:middleware RoleMiddleware
```

**app/Http/Middleware/RoleMiddleware.php**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (auth()->user()->role !== $role) {
            abort(403, 'Unauthorized Access');
        }

        return $next($request);
    }
}
```

---

##  Step 7: Register Middleware Alias (Laravel 12)

**bootstrap/app.php**

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
```

---

##  Step 8: Create Controllers

```bash
php artisan make:controller AdminController

php artisan make:controller CustomerController
```

**app/Http/Controllers/AdminController.php**

```php
<?php

namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function dashboard()
    {
        return "Welcome Admin 🔥";
    }
}
```

**app/Http/Controllers/CustomerController.php**

```php
<?php

namespace App\Http\Controllers;

class CustomerController extends Controller
{
    public function dashboard()
    {
        return "Welcome Customer 👋";
    }
}
```

---

##  Step 9: Protect Routes by Role

**routes/web.php**

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard']);
});

require __DIR__.'/auth.php';
```

---

##  Step 10: Make an Admin User

Open your database → users table → change role value to:

```
admin
```

---

##  Step 11: Clear Cache

```bash
php artisan optimize:clear

composer dump-autoload
```

---

##  Step 12: Testing Role-Based Access (Single User)

### 1. Register One User

Go to the register page and create one account.

### 2. Make the User Admin

Open database → users table → find that user and set:

```
role = admin
```
<img width="1381" height="70" alt="logo" src="https://github.com/user-attachments/assets/feaf859d-6656-48df-bd9a-9667cb2f22da" />


Test URLs:

```
127.0.0.1:8000/admin/dashboard
```
<img width="492" height="117" alt="Screenshot 2026-01-27 110718" src="https://github.com/user-attachments/assets/cfb1545c-23aa-4a4b-a0e5-86eac0b7ab9e" />

```
127.0.0.1:8000/customer/dashboard
```
<img width="1568" height="707" alt="Screenshot 2026-01-27 110729" src="https://github.com/user-attachments/assets/66093b73-a3fd-4d76-b75f-726e3d0f27c6" />


### 3. Change Role to Customer

Now go back to the database and update the same user:

```
role = customer
```
<img width="1384" height="69" alt="Screenshot 2026-01-27 110833" src="https://github.com/user-attachments/assets/8b9203b6-1d3c-407a-943b-b6e9725849b5" />


Test URLs again:

```
127.0.0.1:8000/customer/dashboard
```
<img width="443" height="105" alt="Screenshot 2026-01-27 110847" src="https://github.com/user-attachments/assets/0c90b5d5-a5f5-492c-9ad6-3d00fec89661" />

```
127.0.0.1:8000/admin/dashboard
```
<img width="1372" height="622" alt="Screenshot 2026-01-27 110857" src="https://github.com/user-attachments/assets/af5feee9-cfab-4491-a4ac-50f9d7a9a5d2" />

---

## Final Result

You have successfully implemented:

* Laravel authentication
* Role column in users table
* Custom role middleware
* Middleware alias in Laravel 12 style
* Role-protected routes
* 403 protection for unauthorized users

---
