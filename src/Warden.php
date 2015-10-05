<?php

namespace Warden;

use Warden\WardenEvents;
use Warden\Collector\CollectorInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * The base class for warden, to start/stop and control various actions
 *
 * @package Warden
 * @author Dan Cox
 */
class Warden
{
    /**
     * The event dispatcher
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatch;

    /**
     * Set up class dependencies
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     * @author Dan Cox
     */
    public function __construct($dispatcher = NULL)
    {
        $this->dispatch = (!is_null($dispatcher) ? $dispatcher : new EventDispatcher);
    }

} // END class Warden
