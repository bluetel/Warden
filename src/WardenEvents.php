<?php

namespace Warden;

/**
 * Events class to keep track of the various event stages
 *
 * @package Warden
 * @author Dan Cox
 */
final class WardenEvents
{
    /**
     * The event name for when warden starts
     *
     * @var String
     */
    const WARDEN_START = 'warden.start';

    /**
     * The event name for when warden ends
     *
     * @var String
     */
    const WARDEN_END = 'warden.end';

    /**
     * The event name for when a governor method is called
     *
     * @var String
     */
    const BEFORE_METHOD = 'before.method';

    /**
     * The event name for after governor method execution
     *
     * @var String
     */
    const AFTER_METHOD = 'after.method';

} // END class WardenEvents
