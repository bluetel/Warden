<?php

namespace Warden\Governor;

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
     * Set up class dependencies
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatch
     * @param Object $service
     */
    public function __construct(EventDispatcher $dispatch, $service)
    {
        $this->dispatch = $dispatch;
        $this->service = $service;
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
        return call_user_func_array([$this->service, $method], $params);
    }

} // END class GovernorDecorator
