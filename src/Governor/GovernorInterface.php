<?php

namespace Warden\Governor;

use Warden\Events\BeforeMethodEvent;
use Warden\Events\AfterMethodEvent;

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
     * Handler for when a method is called
     *
     * @return void
     */
    public function beforeMethodFire(BeforeMethodEvent $event);

    /**
     * Handler for after a method is called
     *
     * @return void
     */
    public function afterMethodFire(AfterMethodEvent $event);
}
