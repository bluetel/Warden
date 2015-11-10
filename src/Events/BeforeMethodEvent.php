<?php

namespace Warden\Events;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event thats fired when a governor class' methods are called
 *
 * @package Warden
 * @subpackage Event
 * @author Dan Cox
 */
class BeforeMethodEvent extends Event
{
    /**
     * The alias of the governor
     *
     * @var String
     */
    public $alias;

    /**
     * The name of the called method
     *
     * @var String
     */
    public $method;

    /**
     * The param bag instance
     *
     * @var Warden\Collector\CollectorParamBag
     */
    public $params;

    /**
     * Set up class dependencies
     *
     * @param String $alias
     * @param String $method
     */
    public function __construct($alias, $method, $paramBag)
    {
        $this->alias = $alias;
        $this->method = $method;
        $this->params = $paramBag;
    }

} // END class BeforeMethodEvent extends Event
