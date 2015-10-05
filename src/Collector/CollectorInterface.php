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
     * Registers an action to perform upon starting warden
     *
     * @return void
     * @author Dan Cox
     */
    public function register($eventDispatcher);

    /**
     * Returns an array describing the data and data types this will collect
     *
     * @return Array
     * @author Dan Cox
     */
    public function describe();

}
