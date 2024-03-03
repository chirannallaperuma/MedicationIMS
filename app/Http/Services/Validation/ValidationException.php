<?php

namespace App\Http\Services\Validation;

use Exception;

class ValidationException extends Exception
{
    /**
     * errors
     *
     * @var mixed
     */
    protected $errors;

    /**
     * __construct
     *
     * @param  mixed $message
     * @param  mixed $errors
     * @return void
     */
    public function __construct($message, $errors)
    {
        $this->errors = $errors;
		parent::__construct($message);
    }

    /**
     * getErrors
     *
     * @return void
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
