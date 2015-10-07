<?php

namespace Warden\Exceptions;

/**
 * Exception class for when a limit has been hit
 *
 * @package Warden
 * @subpackage Exceptions
 * @author Dan Cox
 */
class LimitExceededException extends \Exception
{
    /**
     * The actual value from the collector
     *
     * @var Mixed
     */
    protected $actual;

    /**
     * The set limit
     *
     * @var Mixed
     */
    protected $limit;

    /**
     * The name of the limit collector node
     *
     * @var String
     */
    protected $name;

    /**
     * fire exception
     *
     * @param Mixed $actual
     * @param Mixed $limit
     * @param String $name
     * @param Integer $code
     * @param Exception $previous
     * @author Dan Cox
     */
    public function __construct($actual, $limit, $name, $code = 0, \Exception $previous = NULL)
    {
        $this->actual = $actual;
        $this->limit = $limit;
        $this->name = $name;

        parent::__construct(
            "Warden Limit: The $name collector node recorded $actual, the limit is $limit.",
            $code,
            $previous
        );
    }

    /**
     * Returns the actual value
     *
     * @return Mixed
     * @author Dan Cox
     */
    public function getActual()
    {
        return $this->actual;
    }

    /**
     * Returns the limit value
     *
     * @return Mixed
     * @author Dan Cox
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Returns the limit name
     *
     * @return String
     * @author Dan Cox
     */
    public function getName()
    {
        return $this->name;
    }

} // END class LimitExceededException extends \Exception
