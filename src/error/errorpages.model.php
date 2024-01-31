<?php 

namespace Controller\Error\Pages;

# class returning HTTP response code following https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
readonly class ErrorPages {

    private static function getHttpErrorPage(int $header) {
        return "status/$header.html";
    }

    private static function redirectToPage(string $link, int $httpStatus = 200) {
        http_response_code($httpStatus);
        include($link);
        die();
    }

    public static function displayInternalServerError() {
        $httpStatus = 500;
        self::redirectToPage(self::getHttpErrorPage($httpStatus), $httpStatus);
    }

    public static function displayServiceUnavailable() {
        $httpStatus = 503;
        self::redirectToPage(self::getHttpErrorPage($httpStatus), $httpStatus);
    }

    public static function displayForbidden() {
        $httpStatus = 403;
        self::redirectToPage(self::getHttpErrorPage($httpStatus), $httpStatus);
    }

    public static function displayIAmATeapot() {
        $httpStatus = 418;
        self::redirectToPage(self::getHttpErrorPage($httpStatus), $httpStatus);
    }
 
    public static function displayUserInputError() {
        
    }
}