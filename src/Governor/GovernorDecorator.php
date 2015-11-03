<?php

namespace Warden\Governor;

use Warden\WardenEvents;
use Warden\Events\BeforeMethodEvent;
use Warden\Events\AfterMethodEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * The decorator class for a governor service class
 *
 * @package Warden
 * @subpackage Governor
 * @author Dan Cox
 */
class GovernorDecorator
{
    /**
     * Instance of the event dispatcher
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatch;

    /**
     * The service class that this is decorating
     *
     * @var Object
     */
    protected $service;

    /**
     * The alias of the governor class
     *
     * @var String
     */
    protected $alias;

    /**
     * Set up class dependencies
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatch
     * @param Object $service
     * @param String $alias
     */
    public function __construct(EventDispatcher $dispatch, $service, $alias)
    {
        $this->dispatch = $dispatch;
        $this->service = $service;
        $this->alias = $alias;
    }

    /**
     * Call method, performs the call and sends the result to the dispatcher
     *
     * @param String $method
     * @param Array $params
     * @return Mixed
     */
    public function __call($method, array $params = array())
    {
        $before = new BeforeMethodEvent($this->alias, $method);

        $this->dispatch->dispatch(
            WardenEvents::BEFORE_METHOD,
            $before
        );

        $returnValue = call_user_func_array([$this->service, $method], $params);

        $after = new AfterMethodEvent($this->alias, $method, $returnValue);

        $this->dispatch->dispatch(
            WardenEvents::AFTER_METHOD,
            $after
        );

        return $returnValue;
    }

} // END class GovernorDecorator
