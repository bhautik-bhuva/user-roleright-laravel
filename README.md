# user-roleright-laravel

User Role and Right Management With Route

## Features

- Manage users, roles, and permissions
- Assign roles and permissions to users
- Middleware for route protection
- Easy integration with Laravel projects

## Installation

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

## uninstall

```bash
composer run-script remove-useraccess
```

     * Checks for the existence of the `routes/UserAccessDynamicRoutes.php` file:
     *    - If it exists, removes the dynamic route inclusion from `routes/web.php` and deletes the dynamic routes file.
     *    - If unable to open `routes/web.php` for writing, outputs an error message.
     * Opens and decodes the `composer.json` file, removes the `remove-useraccess` script entry, and writes the updated JSON back to the file.
     * Outputs an informational message indicating the completion of the package removal process.

## Usage

## License

MIT
