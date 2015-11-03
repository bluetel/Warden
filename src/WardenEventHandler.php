<?php

namespace Warden;

use Warden\WardenEvents;
use Warden\Events\BeforeMethodEvent;
use Warden\Events\AfterMethodEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Generic event handler
 *
 * @package Warden
 * @author Dan Cox
 */
class WardenEventHandler
{
    /**
     * Instance of the event dispatcher
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatch;

    /**
     * Instance of warden
     *
     * @var \Warden\Warden
     */
    protected $warden;

    /**
     * Set up class dependencies
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatch
     * @param \Warden\Warden $warden
     */
    public function __construct(EventDispatcher $dispatch, Warden $warden)
    {
        $this->dispatch = $dispatch;
        $this->warden = $warden;
    }

    /**
     * Registers the events on the dispatcher
     *
     * @return void
     */
    public function register()
    {
        $this->dispatch->addListener(WardenEvents::BEFORE_METHOD, [$this, 'onBeforeMethod']);
        $this->dispatch->addListener(WardenEvents::AFTER_METHOD, [$this, 'onAfterMethod']);
    }

    /**
     * Handler for the Before method governor event
     *
     * @param \Warden\Events\BeforeMethodEvent $event
     * @return void
     */
    public function onBeforeMethod(BeforeMethodEvent $event)
    {
        $governor = $this->warden->getGovernorHandler($event->alias);
        $governor->beforeMethodFire($event);
    }

    /**
     * Handler for the After method governor event
     *
     * @param \Warden\Events\AfterMethodEvent $event
     * @return void
     */
    public function onAfterMethod(AfterMethodEvent $event)
    {
        $governor = $this->warden->getGovernorHandler($event->alias);
        $governor->afterMethodFire($event);
    }

} // END class WardenEventHandler
