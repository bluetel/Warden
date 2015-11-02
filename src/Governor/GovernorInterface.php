<?php

namespace Warden\Governor;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * The govenor interface, allows the creation of custom collection tools
 *
 * @package Warden
 * @subpackage Govenor
 * @author Dan Cox
 */
Interface GovernorInterface
{
    /**
     * Returns the service class that should be used to gather information
     *
     * @return Object
     */
    public function getService();

    /**
     * Returns the alias for the governor class
     *
     * @return String
     */
    public function getAlias();

    /**
     * Register events on the dispatcher
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatch
     * @return void
     */
    public function registerEvents(EventDispatcher $dispatch);
}
