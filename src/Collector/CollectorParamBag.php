<?php

namespace Warden\Collector;

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
        $this->data[$key]['limit'] = $value;
        return $this;
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
        $this->data[$key]['expression'] = $value;
        return $this;
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
        $this->data[$key]['value'] = $value;
        return $this;
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

    /**
     * Sets the collector value
     *
     * @param String $key
     * @param Mixed $value
     * @return CollectorParamBag
     */
    public function __set($key, $value)
    {
        $this->data[$key] = array_merge($this->data[$key], ['value' => $value]);
        return $this;
    }

    /**
     * Returns the collector value
     *
     * @param String $key
     * @return Mixed
     */
    public function __get($key)
    {
        return $this->data[$key]['value'];
    }

} // END class CollectorParamBag
