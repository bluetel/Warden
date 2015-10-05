<?php

use Warden\WardenEvents;
use Warden\Collector\CollectorParamBag;
use Warden\Collector\Stopwatch\StopwatchCollector;
use Warden\Events\StartEvent;
use Warden\Events\StopEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Test case for the stopwatch collector
 *
 * @package Warden
 * @subpackage Tests
 * @author Dan Cox
 */
class StopwatchCollectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * An instance of the symfony events dispatcher
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventsDispatcher;

    /**
     * Instance of the collector param bag
     *
     * @var \Warden\Collector\CollectorParamBag
     */
    protected $collectorParamBag;

    /**
     * Instance of the start event
     *
     * @var \Warden\Events\StartEvent
     */
    protected $startEvent;

    /**
     * Instance of the stop event
     *
     * @var \Warden\Events\StopEvent
     */
    protected $stopEvent;

    /**
     * Set up test env
     *
     * @return void
     * @author Dan Cox
     */
    public function setUp()
    {
        $this->eventsDispatcher = new EventDispatcher;
        $this->collectorParamBag = new CollectorParamBag;

        $this->collectorParamBag->add('request_time', [
            'type'              => 'integer',
            'default'           => 100,
            'limit'             => 100,
            'value'             => null
        ]);

        $this->collectorParamBag->add('request_memory', [
            'type'              => 'integer',
            'default'           => 1024,
            'limit'             => 1024,
            'value'             => null
        ]);

        $this->startEvent = new StartEvent;
        $this->stopEvent = new StopEvent($this->collectorParamBag);
    }

    /**
     * Test that it actually collects data
     *
     * @return void
     * @author Dan Cox
     */
    public function test_it_collects_data()
    {
        $collector = new StopwatchCollector();
        $collector->register($this->eventsDispatcher);

        // Fire the events
        $this->eventsDispatcher->dispatch(WardenEvents::WARDEN_START, $this->startEvent);

        usleep(600);

        $event = $this->eventsDispatcher->dispatch(WardenEvents::WARDEN_END, $this->stopEvent);

        $this->assertNotNull($event->params->getValue('request_time'));
        $this->assertNotNull($event->params->getValue('request_memory'));
    }

} // END class StopwatchCollectorTest extends \PHPUnit_Framework_TestCase
