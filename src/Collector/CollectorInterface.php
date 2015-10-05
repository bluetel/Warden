<?php

namespace Warden\Collector;

/**
 * Collector interface governs how and what information is available
 *
 * @package Warden
 * @subpackage Collectors
 * @author Dan Cox
 */
interface CollectorInterface
{

    /**
     * Triggers the collection of request/limit information
     *
     * @return void
     * @author Dan Cox
     */
    public function collect();

    /**
     * Registers an action to perform upon starting warden
     *
     * @return void
     * @author Dan Cox
     */
    public function register($eventDispatcher);

    /**
     * Detatches the action and performs any methods needed to collect data
     *
     * @return void
     * @author Dan Cox
     */
    public function detatch();

    /**
     * Returns an array describing the data and data types this will collect
     *
     * @return Array
     * @author Dan Cox
     */
    public function describe();

}
