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
     * Set up class dependencies
     *
     * @param String $alias
     * @param String $method
     */
    public function __construct($alias, $method)
    {
        $this->alias = $alias;
        $this->method = $method;
    }

} // END class BeforeMethodEvent extends Event
