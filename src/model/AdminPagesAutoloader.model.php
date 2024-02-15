<?php

namespace Model;

use Autoloader;

/**
 * Class for loading and constructing the appropriate class for the admin pages, given the request URI.
 */
class AdminPagesAutoloader
{

    private static string $requestURI;
    public static function register($requestURI)
    {
        self::$requestURI = self::removeGetRequest($requestURI);
        spl_autoload_register([__CLASS__, 'fileLoader']);
    }


    /**
     * Function working as an autoloader for the Admin Pages.
     * 
     * Function is called by the spl_autoloader_register function.
     * @return null
     * Given a request the appropriate file name is reconstructed, if it exist it is included.
     * @link https://www.php.net/manual/en/function.spl-autoload.php
     */
    public static function fileLoader(string $className, bool $param = null): bool
    {
        $classParts = explode('\\', $className);
        if (sizeof($classParts) < 2) {
            return false;
        }

        $filename = substr(array_pop($classParts), 10) . '.' . lcfirst($classParts[0]) . '.php';
        $directoryPath = implode('/', array_map('lcfirst', $classParts));
        $fullPath = __DIR__ . '/../' . $directoryPath . '/' . $filename;

        if (file_exists($fullPath)) {
            require $fullPath;
            return true;
        }

        return false;
    }


    /**
     * Function for constructing the corresponding class name given the request URI.
     * 
     * @return string It is not ensured that the returned class exists.
     */
    public static function extractClassName(): string
    {
        $headerParts = explode('/', self::$requestURI);
        return 'View\Admin\Pages\AdminPages' . implode('', array_map('ucfirst', array_slice($headerParts, 2)));
    }


    /**
     * Function for removing the GET request from a header.
     * 
     * @param string $header Header with or without GET request.
     * @return string Header without the GET request.
     */
    private static function removeGetRequest(string $header): string
    {
        $getRequestStart = self::getGetRequestStart($header);
        return substr($header, 0, $getRequestStart);
    }


    /**
     * Function for finding the start of the GET request in the header.
     * 
     * @return int Index of the start of the GET request.
     * If none is found, the last index of the array is returned.
     */
    private static function getGetRequestStart(string $header): int
    {
        $left = 0;
        $getRequestStart = $headerLength = strlen($header);
        while ($left < $headerLength) {
            if ($header[$left] == '?') {
                $getRequestStart = $left;
                break;
            }
            $left++;
        }

        return $getRequestStart;
    }
}
