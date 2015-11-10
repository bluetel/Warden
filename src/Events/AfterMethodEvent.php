<?php

namespace Warden\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event class for after governor method execution
 *
 * @package Warden
 * @subpackage Events
 * @author Dan Cox
 */
class AfterMethodEvent extends Event
{
    /**
     * The governor alias
     *
     * @var String
     */
    public $alias;

    /**
     * The name of the method
     *
     * @var String
     */
    public $method;

    /**
     * The return value from the method
     *
     * @var Mixed
     */
    public $returnValue;

    /**
     * Instance of the param bag
     *
     * @var Warden\Collector\CollectorParamBag
     */
    public $params;

    /**
     * Set up class dependencies
     *
     * @param String $alias
     * @param String $method
     * @param Mixed $returnValue
     */
    public function __construct($alias, $method, $returnValue, $params)
    {
        $this->alias = $alias;
        $this->method = $method;
        $this->returnValue = $returnValue;
        $this->params = $params;
    }

} // END class AfterMethodEvent extends Event


