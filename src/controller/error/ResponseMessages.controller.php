<?php

namespace Controller\Error;

/**
 * Class to display errors, information and warning to user.
 */
class ResponseMessages
{
    private static string $errorHeading = '';
    private static string $errorMessage = '';
    private static string $icon;

    /**
     * Variable to store the type of response message.
     * 
     * Existing response messages:
     * - error
     * - info
     * - success
     */
    private static string $type = '';

    /**
     * Function to display response messages found in the header.
     */
    public static function displayMessage()
    {
        if (self::hasResponseMessage()) {

            // setting the style of the window
            // and getting the right icon
            self::setResponseStyle();

            // get the errormessage
            self::getErrorMessage();

            // render error message
            self::renderResponseMessage();
        }
    }

    /**
     * Function to check if the header contains a response message.
     */
    private static function hasResponseMessage()
    {
        if (isset($_GET['err']) and !empty($_GET['err'])) {
            return true;
        }

        return false;
    }

    /**
     * Function to render the response message.
     */
    private static function renderResponseMessage()
    {
?>
        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
            <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
            </symbol>
            <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
            </symbol>
            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
            </symbol>
        </svg>

        <div class="alert alert-<?= self::$type ?>" role="alert">
            <div class="d-flex align-items-center">
                <?= self::$icon ?>
                <div class="ms-3">
                    <h5 class="alert-heading"><?= self::$errorHeading ?></h5>
                    <p class="m-0"><?= self::$errorMessage ?></p>
                </div>
            </div>
        </div>

<?php
    }

    /**
     * Function to load the get request into in class variables.
     */
    private static function getErrorMessage()
    {
        self::$errorHeading = ucfirst(htmlspecialchars($_GET['err']));
        self::$errorMessage = isset($_GET['msg']) ? htmlspecialchars($_GET['msg']) : '';
    }

    private static function setResponseStyle()
    {
        $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'error';
        switch ($type) {
            case 'info':
                self::$icon = '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>';
                self::$type = 'primary';
                break;
            case 'success':
                self::$icon = '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>';
                self::$type = 'success';
                break;
            default:
                self::$icon = '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>';
                self::$type = 'danger';
                break;
        }
    }
}
