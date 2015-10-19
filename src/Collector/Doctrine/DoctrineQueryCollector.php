<?php

namespace Warden\Collector\Doctrine;

use Warden\WardenEvents;
use Warden\Collector\CollectorInterface;
use Warden\Collector\CollectorParamBag;
use Warden\Events\StartEvent;
use Warden\Events\StopEvent;
use Doctrine\DBAL\Logging\DebugStack;

/**
 * Collector class for doctrine queries
 *
 * @package Warden
 * @subpackage Collectors
 * @author Dan Cox
 */
class DoctrineQueryCollector implements CollectorInterface
{
    /**
     * Instance of the entity manager
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Instance of the debug stack
     *
     * @var \Doctrine\DBAL\Logging\DebugStack
     */
    protected $stack;

    /**
     * Set up class dependencies
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param \Doctrine\DBAL\Logging\DebugStack
     */
    public function __construct($em, $stack = NULL)
    {
        $this->em = $em;
        $this->stack = (!is_null($stack) ? $stack : new DebugStack);
    }

    /**
     * Describes the contents of the collector
     *
     * @return Array
     */
    public function describe()
    {
        return array(
            'query_limit'   => ['type' => 'integer']
        );
    }

    /**
     * Registers the debug stack to the entity manager
     *
     * @return void
     */
    public function registerStack()
    {
        $this->em->getConfiguration()->setSQLLogger($this->stack);
    }

    /**
     * Registers the collector through warden's event dispatcher
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @return void
     */
    public function register($eventDispatcher)
    {
        $this->stack = new DebugStack;

        $eventDispatcher->addListener(WardenEvents::WARDEN_START, function (StartEvent $event) {
            $this->em->getConfiguration()->setSQLLogger($this->stack);
        });

        $eventDispatcher->addListener(WardenEvents::WARDEN_END, function(StopEvent $event) {
            $event->params->setValue('query_limit', $this->stack->currentQuery);
        });
    }
} // END class DoctrineQueryCollector implements CollectorInterface
