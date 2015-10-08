<?php

namespace Warden;

use Warden\WardenEvents;
use Warden\Analyser;
use Warden\Events\StartEvent;
use Warden\Events\StopEvent;
use Warden\Collector\CollectorInterface;
use Warden\Collector\CollectorParamBag;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Yaml\Parser;

/**
 * The base class for warden, to start/stop and control various actions
 *
 * @package Warden
 * @author Dan Cox
 */
class Warden
{
    /**
     * The event dispatcher
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatch;

    /**
     * The finished param bag
     *
     * @var \Warden\Collector\CollectorParamBag
     */
    protected $params;

    /**
     * Instance of the YAML parser
     *
     * @var \Symfony\Component\Yaml\Parser
     */
    protected $parser;

    /**
     * An array of registered collectors
     *
     * @var Array
     */
    protected $collectors;

    /**
     * Raw settings from the YAML file
     *
     * @var Array
     */
    protected $settings;

    /**
     * The start event used when warden starts
     *
     * @var \Warden\Events\StartEvent
     */
    protected $start;

    /**
     * The end event
     *
     * @var \Warden\Events\StopEvent
     */
    protected $stop;

    /**
     * Set up class dependencies
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    public function __construct($dispatcher = NULL)
    {
        $this->dispatch = (!is_null($dispatcher) ? $dispatcher : new EventDispatcher);

        $this->params = new CollectorParamBag;
        $this->parser = new Parser;
        $this->collectors = array();
    }

    /**
     * Reads a YAML file to determine settings
     *
     * @param String $file
     * @return void
     */
    public function setUp($file)
    {
        $this->settings = $this->parser->parse(file_get_contents($file));

        if (is_array($this->settings['warden']['collectors'])) {

            $this->createCollectors($this->settings['warden']['collectors']);
            $this->registerCollectors();
            $this->initEvents();
        }

        if (is_array($this->settings['warden']['limits'])) {
           $this->updateLimits($this->settings['warden']['limits']); 
        }
    }

    /**
     * Updates limits
     *
     * @param Array $limits
     * @return void
     */
    public function updateLimits(array $limits)
    {
        foreach ($limits as $key => $limit) {
            $this->params->setLimit($key, $limit);
        }
    }

    /**
     * Creates an instance of the collectors specified in settings
     * and runs their describe methods
     *
     * @param Array $collectors
     * @return void
     */
    public function createCollectors(array $collectors)
    {
        $descriptions = array();

        // Init each class, and gather their describe info
        foreach ($collectors as $key => $class) {

            $rfl = new \ReflectionClass($class);
            $ins = $rfl->newInstance();

            $this->collectors[$key] = $ins;

            $descriptions = array_merge($descriptions, $ins->describe());
        }

        // Add the describe info to the param bag
        foreach ($descriptions as $key => $description) {

            $this->params->add($key, $description);
        }
    }

    /**
     * Registers the array of collectors
     *
     * @return void
     */
    public function registerCollectors()
    {
        foreach ($this->collectors as $collector) {
            $collector->register($this->dispatch);
        }
    }

    /**
     * Creates the events needed to run warden
     *
     * @return void
     */
    public function initEvents()
    {
        $this->start = new StartEvent;
        $this->stop = new StopEvent($this->params);
    }

    /**
     * Triggers the start event to begin collecting information
     *
     * @return void
     */
    public function start()
    {
        $this->dispatch->dispatch(WardenEvents::WARDEN_START, $this->start);
    }

    /**
     * Triggers the end event to finish collecting and analyse
     * collected data
     *
     * @return void
     */
    public function stop()
    {
        $this->stop = $this->dispatch->dispatch(WardenEvents::WARDEN_END, $this->stop);
        $this->params = $this->stop->params;

        // Send the results to the analyser
        $this->analyseResults();
    }

    /**
     * Checks the results straight after the stop event is called
     *
     * @return void
     */
    public function analyseResults()
    {
        $analyser = new Analyser($this->params);
        $analyser->checkResults();
    }

    /**
     * Returns the parambag instance
     *
     * @return \Warden\Collector\CollectorParamBag
     */
    public function getParams()
    {
        return $this->params;
    }

} // END class Warden
