<?php

/**
 * Autoloader Class
 * 
 * This Autoloader follows the PSR-4 standards for autoloading classes.
 * The base directory is the `src/` directory.
 * 
 * Example:
 * `Model\Database` => `src/model/Database.model.php`
 * 
 * The Autoloader is initialized as follows:
 * ```php
 * require __DIR__ . '/Autoloader.controller.php';
 * Autoloader::register();
 * ```
 * @see https://www.php-fig.org/psr/psr-4/
 */
class Autoloader
{

    /**
     * The function registers the Autoloader.
     * It enables automatic class loading based on PSR-4 standards.
     * The base directory for the autoloader is `src/`.
     */
    public static function register(): void
    {
        spl_autoload_register([__CLASS__, 'loader']);
    }

    private static function loader(string $className): bool
    {
                $classParts = explode('\\', $className);

        $filename = array_pop($classParts) . '.' . lcfirst($classParts[0]) . '.php';
        $directoryPath = implode('/', array_map('lcfirst', $classParts));

        $fullPath = __DIR__ . '/../' . $directoryPath . '/' . $filename;

                // check if the file exists
        if (file_exists($fullPath)) {
            require $fullPath;
            return true;
        }

        return false;
    }
}
