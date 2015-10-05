<?php

namespace Warden\Events;

use Symfony\Component\EventDispatcher\Event;
use Warden\Collector\CollectorParamBag;

/**
 * Class representing the Warden End event
 *
 * @package Warden
 * @subpackage Events
 * @author Dan Cox
 */
class StopEvent extends Event
{
    /**
     * Instance of a collector param bag
     *
     * @var \Warden\Collector\CollectorParamBag
     */
    public $params;

    /**
     * Set class dependencies
     *
     * @param \Warden\Collector\CollectorParamBag $params
     * @author Dan Cox
     */
    public function __construct(CollectorParamBag $params)
    {
        $this->params = $params;
    }

} // END class StopEvent extends Event
