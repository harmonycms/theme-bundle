<?php

namespace Harmony\Bundle\ThemeBundle\Json;

use Exception;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class JsonValidationException extends Exception
{

    protected $errors;

    /**
     * Construct the exception. Note: The message is NOT binary safe.
     *
     * @link  https://php.net/manual/en/exception.construct.php
     *
     * @param string    $message  [optional] The Exception message to throw.
     * @param array     $errors
     * @param Exception $previous [optional] The previous throwable used for the exception chaining.
     *
     * @since 5.1.0
     */
    public function __construct($message, $errors = [], Exception $previous = null)
    {
        $this->errors = $errors;
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}