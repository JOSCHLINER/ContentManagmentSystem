<?php

namespace Controller\Error;

/**
 * Class to display errors, information and warning to user.
 */
class ResponseMessages
{
    private static string $errorHeading = '';
    private static string $errorMessage = '';
    private static string $errorCode = '';

    /**
     * Variable to store the type of response message.
     * 
     * Existing response messages:
     * - warning
     * - error
     * - success
     */
    private static string $type = '';

    /**
     * Function to display response messages found in the header.
     */
    public static function displayMessage()
    {
        if (self::hasResponseMessage()) {

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

    private static function renderResponseMessage()
    {
?>
        <div class="response_message <?= self::$type ?> ">
            <span class="response_message error_heading">
                <b>
                    <?= self::$errorHeading ?>
                </b>
            </span>
            <span class="response_message error_code">
                <?= self::$errorMessage ?>
            </span>
            <p class="response_message error_message">
                <?= self::$errorCode ?>
            </p>
        </div>
<?php
    }

    private static function getErrorMessage()
    {
        self::$type = $_GET['err'];
        self::$errorHeading = isset($_GET['err']) ? '' : $_GET['err'];
        self::$errorMessage = isset($_GET['msg']) ? '' : $_GET['msg'];
        self::$errorCode = isset($_GET['code']) ? '' : $_GET['code'];
    }
}
