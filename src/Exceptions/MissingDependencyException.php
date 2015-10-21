<?php

namespace Warden\Exceptions;

/**
 * Exception class thrown when a dependency is missing
 *
 * @package Warden
 * @subpackage Exceptions
 * @author Dan Cox
 */
class MissingDependencyException extends \Exception
{
    /**
     * The name of the expected dependency
     *
     * @var String
     */
    protected $dependency;

    /**
     * Fire the exception
     *
     * @param String $dependency
     * @param Integer $code
     * @param \Exception $previous
     */
    public function __construct($dependency, $code = 0, \Exception $previous = NULL)
    {
        $this->dependency = $dependency;

        parent::__construct(
            "Missing or invalid dependency ($dependency) could not be found.",
            $code,
            $previous
        );
    }

    /**
     * Returns the value for dependency
     *
     * @return String
     */
    public function getDependency()
    {
        return $this->dependency;
    }

} // END class MissingDependencyException extends \Exception
