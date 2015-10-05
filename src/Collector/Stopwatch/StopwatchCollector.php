<?php

namespace Warden\Collector\Stopwatch;

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
     * @var \Symfony\Component\StopWatch\StopWatch
     */
    protected $stopwatch;

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
     * Collects information via the stopwatch component
     *
     * @return void
     * @author Dan Cox
     */
    public function collect()
    {

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
    }

    /**
     * Detatches the event and collects nessecary data
     *
     * @return void
     * @author Dan Cox
     */
    public function detatch()
    {
    }

} // END class StopwatchCollector implements CollectorInterface
