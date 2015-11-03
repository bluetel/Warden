<?php

namespace Warden\Exceptions;

/**
 * Exception class fired when a governor class can not be found
 *
 * @package Warden
 * @subpackage Exceptions
 * @author Dan Cox
 */
class GovernorNotFoundException extends \Exception
{
    /**
     * The name of the selected governor
     *
     * @var String
     */
    protected $name;

    /**
     * Fire exception
     *
     * @param String $name
     * @param Integer $code
     * @param Exception $previous
     */
    public function __construct($name, $code = 0, \Exception $previous = null)
    {
        $this->name = $name;

        parent::__construct(
            "The $name governor could not be found, have you remembered to register it?",
            $code,
            $previous
        );
    }

    /**
     * Returns the name var
     *
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

} // END class GovernorNotFoundException extends \Exception
