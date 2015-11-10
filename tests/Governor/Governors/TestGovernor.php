<?php

namespace Warden\Tests\Governor\Governors;

use Warden\Collector\CollectorParamBag;
use Warden\Governor\GovernorInterface;
use Warden\Events\BeforeMethodEvent;
use Warden\Events\AfterMethodEvent;
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
     * Handler for a method call
     *
     * @param \Warden\Events\BeforeMethodEvent $event
     * @return void
     */
    public function beforeMethodFire(BeforeMethodEvent $event)
    {
        $event->params->add('governor_test', [
            'limit'     => 50
        ]);
    }

    /**
     * Handler for after a method is called
     *
     * @param \Warden\Events\AfterMethodEvent $event
     * @return void
     */
    public function afterMethodFire(AfterMethodEvent $event)
    {
        $event->params->setValue('governor_test', 52);
    }

} // END class TestGovernor implements GovernorInterface
