<?php

namespace Warden\Exceptions;

/**
 * Exception class thrown when an invalid data type is set.
 *
 * @package Warden
 * @subpackage Exceptions
 * @author Dan Cox
 */
class InvalidNodeDataTypeException extends \Exception
{
    /**
     * The data type attempted
     *
     * @var String
     */
    protected $type;

    /**
     * The allowed data type for this node
     *
     * @var String
     */
    protected $allowed;

    /**
     * The name of the node
     *
     * @var String
     */
    protected $node;

    /**
     * Fire exception
     *
     * @param String $type
     * @param String $allowed
     * @param String $node
     * @param Integer $code
     * @param \Exception $previous
     */
    public function __construct($type, $allowed, $node, $code = 0, \Exception $previous = NULL)
    {
        $this->type = $type;
        $this->allowed = $allowed;
        $this->node = $node;

        parent::__construct(
            "An invalid data type was specified for the $node node, data type given: $type, data type allowed: $allowed",
            $code,
            $previous
        );
    }

    /**
     * Returns the value for the type variable
     *
     * @return String
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the value for the allowed var
     *
     * @return String
     */
    public function getAllowed()
    {
        return $this->allowed;
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

} // END class InvalidNodeDataTypeException extends \Exception
