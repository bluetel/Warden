<?php

namespace Warden\Collector;

use Warden\Exceptions;

/**
 * The parameter bag for collectors
 *
 * @package Warden
 * @subpackage Collector
 * @author Dan Cox
 */
class CollectorParamBag
{
    /**
     * An array of data
     *
     * @var Array
     */
    protected $data;

    /**
     * An array of default values that a collector should have
     *
     * @var Array
     */
    protected $defaults = [
        'type'          => 'integer',
        'expression'    => 'value >= limit',
        'default'       => 0,
        'value'         => null,
        'limit'         => 0
    ];

    /**
     * Set up class
     *
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * Adds a collectors information to the data array
     *
     * @param String $key
     * @param Array $description
     * @return CollectorParamBag
     */
    public function add($key, array $description)
    {
        $this->data[$key] = array_merge($this->defaults, $description);
        return $this;
    }

    /**
     * Gathers and returns information on a collector node
     *
     * @param String $node
     * @param String $key
     * @return Mixed
     */
    public function nodeInfo($node, $key)
    {
        if (!array_key_exists($node, $this->data)) {
            throw new Exceptions\InvalidCollectorNodeException($node);
        }

        return $this->data[$node][$key];
    }

    /**
     * Sets a node value
     *
     * @param String $node
     * @param String $key
     * @param Mixed $value
     * @return CollectorParamBag
     */
    public function setNodeInfo($node, $key, $value)
    {
        if (!array_key_exists($node, $this->data)) {
            throw new Exceptions\InvalidCollectorNodeException($node);
        }

        $this->checkDataTypes($node, $key, $value);

        $this->data[$node][$key] = $value;
        return $this;
    }

    /**
     * Checks that the type of value matches what the node expects
     *
     * @param String $node
     * @param String $key
     * @param Mixed $value
     * @return void
     */
    public function checkDataTypes($node, $key, $value)
    {
        $type = gettype($value);
        $allowed = $this->data[$node]['type'];
        $keys = array('limit', 'value');

        if ($type != $allowed && in_array($key, $keys)) {
            throw new Exceptions\InvalidNodeDataTypeException($type, $allowed, $node);
        }
    }

    /**
     * Returns the value type as set in the collector `describe` method
     *
     * @param String $key
     * @return String
     */
    public function getType($key)
    {
        return $this->nodeInfo($key, 'type');
    }

    /**
     * Returns the default value specified in the collector `describe` method
     *
     * @param String $key
     * @return Mixed
     */
    public function getDefault($key)
    {
        return $this->nodeInfo($key, 'default');
    }

    /**
     * Returns the limit set by the user, if none is set, it will be the default
     *
     * @param String $key
     * @return Mixed
     */
    public function getLimit($key)
    {
        return $this->nodeInfo($key, 'limit');
    }

    /**
     * Returns the expression for the given key
     *
     * @param String $key
     * @return String
     */
    public function getExpression($key)
    {
        return $this->nodeInfo($key, 'expression');
    }

    /**
     * Returns the value for the specified key
     *
     * @param String $key
     * @return Mixed
     */
    public function getValue($key)
    {
        return $this->nodeInfo($key, 'value');
    }


    /**
     * Sets the limit for the specified key
     *
     * @param String $key
     * @param Mixed $value
     * @return CollectorParamBag
     */
    public function setLimit($key, $value)
    {
        return $this->setNodeInfo($key, 'limit', $value);
    }

    /**
     * Sets the expression value for a node
     *
     * @param String $key
     * @param String $value
     * @return CollectorParamBag
     */
    public function setExpression($key, $value)
    {
        return $this->setNodeInfo($key, 'expression', $value);
    }

    /**
     * Sets the value var for the specified key
     *
     * @param String $key
     * @param Mixed $value
     * @return CollectorParamBag
     */
    public function setValue($key, $value)
    {
        return $this->setNodeInfo($key, 'value', $value);
    }

    /**
     * Returns the data array
     *
     * @return Array
     */
    public function all()
    {
        return $this->data;
    }

} // END class CollectorParamBag
