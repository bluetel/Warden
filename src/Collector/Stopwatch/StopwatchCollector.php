<?php

namespace Warden\Collector\Stopwatch;

use Warden\WardenEvents;
use Warden\Collector\CollectorInterface;
use Warden\Collector\CollectorParamBag;
use Warden\Events\StartEvent;
use Warden\Events\StopEvent;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * The stop watch collector returns memory and time information
 *
 * @package Warden
 * @subpackage Collectors
 * @author Dan Cox
 */
class StopwatchCollector implements CollectorInterface
{

    /**
     * Instance of the stop watch class
     *
     * @var \Symfony\Component\Stopwatch\Stopwatch
     */
    protected $stopwatch;

    /**
     * The finished stop watch event
     *
     * @var \Symfony\Component\Stopwatch\StopwatchEvent
     */
    protected $event;

    /**
     * Set up class dependencies
     *
     * @param \Symfony\Component\StopWatch\StopWatch $stopwatch
     * @author Dan Cox
     */
    public function __construct($stopwatch = NULL)
    {
        $this->stopwatch = (!is_null($stopwatch) ? $stopwatch : new StopWatch);
    }

    /**
     * Returns an array with data and data types
     *
     * @return Array
     * @author Dan Cox
     */
    public function describe()
    {
        return array(
            'request_time'              => ['type' => 'integer'],
            'request_memory'            => ['type' => 'integer']
        );
    }

    /**
     * Registers this collector on the event dispatcher
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher
     * @return void
     * @author Dan Cox
     */
    public function register($eventDispatcher)
    {
        // Add the listeners to start and stop events
        $eventDispatcher->addListener(WardenEvents::WARDEN_START, function(StartEvent $event) {
            $this->stopwatch->start('request');
        });

        $eventDispatcher->addListener(WardenEvents::WARDEN_END, function(StopEvent $event) {
            $sw = $this->stopwatch->stop('request');

            $event->params->setValue('request_time', $sw->getDuration());
            $event->params->setValue('request_memory', $sw->getMemory());
        });
    }

} // END class StopwatchCollector implements CollectorInterface
