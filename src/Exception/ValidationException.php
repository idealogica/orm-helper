<?php
namespace Idealogica\OrmHelper\Exception;

/**
 * Class ValidationException
 * @package Idealogica\OrmHelper\Exception
 */
class ValidationException extends OrmException
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Constructor.
     *
     * @param array $errors
     * @param \Exception $previous
     */
    public function __construct(array $errors, \Exception $previous = null)
    {
        parent::__construct('Data validation failed. Errors: ' . implode('; ', $errors), 0, $previous);
        $this->errors = $errors;
    }

    /**
     * Gets errors array.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Gets error by field name.
     *
     * @param string $name
     * @return string
     */
    public function getError($name)
    {
        return isset($this->errors[$name]) ? $this->errors[$name] : null;
    }
}
