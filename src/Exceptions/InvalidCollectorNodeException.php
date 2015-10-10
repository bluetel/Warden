<?php

namespace Warden\Exceptions;

/**
 * Exception class for when a collector node does not exist
 *
 * @package Warden
 * @subpackage Exceptions
 * @author Dan Cox
 */
class InvalidCollectorNodeException extends \Exception
{
    /**
     * The specified node
     *
     * @var String
     */
    protected $node;

    /**
     * Fire exception
     *
     * @param String $node
     * @param Integer $code
     * @param \Exception $previous
     */
    public function __construct($node, $code = 0, \Exception $previous = null)
    {
        $this->node = $node;

        parent::__construct(
            "The requested collector node $node does not exist.",
            $code,
            $previous
        );
    }

    /**
     * Returns the node value
     *
     * @return String
     */
    public function getNode()
    {
        return $this->node;
    }

} // END class InvalidCollectorNodeException extends \Exception
