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
     * Set up class
     *
     * @author Dan Cox
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
     * @author Dan Cox
     */
    public function add($key, array $description)
    {
        $this->data[$key] = $description;
        return $this;
    }

    /**
     * Returns the value type as set in the collector `describe` method
     *
     * @param String $key
     * @return String
     * @author Dan Cox
     */
    public function getType($key)
    {
        return $this->data[$key]['type'];
    }

    /**
     * Returns the default value specified in the collector `describe` method
     *
     * @param String $key
     * @return Mixed
     * @author Dan Cox
     */
    public function getDefault($key)
    {
        return $this->data[$key]['default'];
    }

    /**
     * Returns the limit set by the user, if none is set, it will be the default
     *
     * @param String $key
     * @return Mixed
     * @author Dan Cox
     */
    public function getLimit($key)
    {
        return $this->data[$key]['limit'];
    }

    /**
     * Sets the limit for the specified key
     *
     * @param String $key
     * @param Mixed $value
     * @return CollectorParamBag
     * @author Dan Cox
     */
    public function setLimit($key, $value)
    {
        $this->data[$key]['limit'] = $value;
        return $this;
    }

    /**
     * Returns the value for the specified key
     *
     * @param String $key
     * @return Mixed
     * @author Dan Cox
     */
    public function getValue($key)
    {
        return $this->data[$key]['value'];
    }

    /**
     * Sets the value var for the specified key
     *
     * @param String $key
     * @param Mixed $value
     * @return CollectorParamBag
     * @author Dan Cox
     */
    public function setValue($key, $value)
    {
        $this->data[$key]['value'] = $value;
        return $this;
    }

    /**
     * Sets the collector value
     *
     * @param String $key
     * @param Mixed $value
     * @return CollectorParamBag
     * @author Dan Cox
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
     * @author Dan Cox
     */
    public function __get($key)
    {
        return $this->data[$key]['value'];
    }

} // END class CollectorParamBag
