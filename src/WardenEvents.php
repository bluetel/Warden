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

} // END class WardenEvents
