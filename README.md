# user-roleright-laravel

User Role and Right Management With Route

## Features

- Manage users, roles, and permissions
- Assign roles and permissions to users
- Middleware for route protection
- Easy integration with Laravel projects

## Installation

Add the following code to the composer.json file located in the root folder of your project.

```bash
"repositories": [
    {
        "type": "vcs",
        "url": "https://gitlab.techaxion.com/composer-plugins/laravel/role-right-management.git"
    }
]
```

```bash
composer require techaxion/user-roleright-laravel
```

    * Checks if the 'module_action' table exists to prevent reinstallation.
    * Publishes package routes using the 'vendor:publish' Artisan command.
    * Runs package-specific migrations for required tables.
    * Seeds the 'module_action' table with initial data.
    * Publishes package configuration and other assets.
    * Appends a require statement for dynamic routes to 'routes/web.php'.
    * Adds a 'remove-useraccess' script to 'composer.json' for easy package removal.

## Initialization

```bash
php artisan useraccess:init-config
```

    * Initializes the package by creating necessary directories and files.
    * Creates a `config/useraccess.php` file for dynamic layout file changes and other configurations.
    * Outputs an informational message indicating successful initialization.

## uninstall

```bash
composer run remove-useraccess
```

     * Checks for the existence of the `routes/UserAccessDynamicRoutes.php` file:
     *    - If it exists, removes the dynamic route inclusion from `routes/web.php` and deletes the dynamic routes file.
     *    - If unable to open `routes/web.php` for writing, outputs an error message.
     * Opens and decodes the `composer.json` file, removes the `remove-useraccess` script entry, and writes the updated JSON back to the file.
     * Outputs an informational message indicating the completion of the package removal process.
     * Checks for the existence of the `config/useraccess.php` file.
     *    - If it exists, removes `useraccess.php` from `config` folder.

## Usage within Plugin

Layout File:
@extends('laravelMain::' . $layout_file)

Push content to above layout file using:
@section(config('useraccess.yield_container'))

Users table from Database:
user_table: users(default table)

Migration URL, where you can get all routes that are assigned to you :
https:test.com/useraccess/menu/migrate

Menu management setting page URL:
https:test.com/useraccess/setting
You can change the configurations like Users table, layout path, Yield Container:
You can also get code to display all dynamic routes

## License

MIT
