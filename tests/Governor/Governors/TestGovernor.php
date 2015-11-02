<?php

namespace Warden\Tests\Governor\Governors;

use Warden\Governor\GovernorInterface;
use Warden\Tests\Governor\Governors\TestService;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A test governor class
 *
 * @package Warden
 * @subpackage Tests
 * @author Dan Cox
 */
class TestGovernor implements GovernorInterface
{
    /**
     * Returns the service class that this uses
     *
     * @return Object
     */
    public function getService()
    {
        return new TestService;
    }

    /**
     * Returns the alias for this class
     *
     * @return String
     */
    public function getAlias()
    {
        return 'testGov';
    }

    /**
     * Registers the events against the dispatcher
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatch
     * @return void
     */
    public function registerEvents(EventDispatcher $dispatch)
    {
    }

} // END class TestGovernor implements GovernorInterface
