<?php

namespace Controller\Admin;

/**
 * Class for loading and constructing the appropriate class for the admin pages, given the request URI.
 */
class AdminPagesLoader
{

    private string $requestURI;
    public function __construct(string $requestURI)
    {
        $this->requestURI = $this->removeGetRequest($requestURI);
        spl_autoload_register(array($this, 'fileLoader'));
    }


    /**
     * Function working as an autoloader for the Admin Pages.
     * 
     * Function is called by the spl_autoloader_register function.
     * @return null
     * Given a request the appropriate file name is reconstructed, if it exist it is included.
     * @link https://www.php.net/manual/en/function.spl-autoload.php
     */
    private function fileLoader(string $className, bool $param = null): ?callable
    {

        $filepath = __DIR__ . '/pages/admin.' . strtolower(substr($className, 10)) . '.view.php';
        if (file_exists($filepath)) {
            include $filepath;
        }

        return null;
    }


    /**
     * Function for constructing the corresponding class name given the request URI.
     * 
     * @return string It is not ensured that the returned class exists.
     */
    public function extractClassName(): string
    {
        $headerParts = explode('/', $this->requestURI);
        return 'AdminPages' . implode('', array_map('ucfirst', array_slice($headerParts, 2)));
    }


    /**
     * Function for removing the GET request from a header.
     * 
     * @param string $header Header with or without GET request.
     * @return string Header without the GET request.
     */
    private function removeGetRequest(string $header): string
    {
        $getRequestStart = $this->getGetRequestStart($header);
        return substr($header, 0, $getRequestStart);
    }


    /**
     * Function for finding the start of the GET request in the header.
     * 
     * @return int Index of the start of the GET request.
     * If none is found, the last index of the array is returned.
     */
    private function getGetRequestStart(string $header): int
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
