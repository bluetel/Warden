<?php

namespace Warden;

use Warden\WardenEventHandler;
use Warden\WardenEvents;
use Warden\Analyser;
use Warden\Events\StartEvent;
use Warden\Events\StopEvent;
use Warden\Collector\CollectorInterface;
use Warden\Collector\CollectorParamBag;
use Warden\Exceptions;
use Warden\Governor\GovernorInterface;
use Warden\Governor\GovernorDecorator;
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
     * Instance of the warden event handler class
     *
     * @var \Warden\WardenEventHandler
     */
    protected $handler;

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
     * An array of registered governors
     *
     * @var Array
     */
    protected $governors;

    /**
     * Raw settings from the YAML file
     *
     * @var Array
     */
    protected $settings;

    /**
     * An array of dependencies for collector classes
     *
     * @var Array
     */
    protected $dependencies;

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
        $this->dependencies = array();

        $this->params = new CollectorParamBag;
        $this->parser = new Parser;
        $this->handler = new WardenEventHandler($this->dispatch, $this);
        $this->collectors = array();
        $this->governors = array();
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

        if (array_key_exists('warden', $this->settings)) {

            if (array_key_exists('collectors', $this->settings['warden'])) {

                $this->createCollectors($this->settings['warden']['collectors']);
                $this->registerCollectors();
                $this->initEvents();
            }

            if (array_key_exists('settings', $this->settings['warden'])) {

               $this->updateSettings($this->settings['warden']['settings']);
            }
        }
    }

    /**
     * Updates settings
     *
     * @param Array $settings
     * @return void
     */
    public function updateSettings(array $settings)
    {
        foreach ($settings as $key => $setting) {

            if (array_key_exists('limit', $setting)) {
                $this->params->setLimit($key, $setting['limit']);
            }

            if (array_key_exists('expression', $setting)) {
                $this->params->setExpression($key, $setting['expression']);
            }
        }
    }

    /**
     * Returns an array of dependencies for collector classes
     *
     * @param Array $dependencies
     * @return Array
     */
    public function resolveDependencies(array $dependencies)
    {
        $resolved = array();

        foreach ($dependencies as $dep) {
            $resolved[] = $this->getDependency($dep);
        }

        return $resolved;
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
        foreach ($collectors as $key => $value) {

            $arguments = (isset($value['arguments']) ? $value['arguments'] : array());
            $args = $this->resolveDependencies($arguments);

            $rfl = new \ReflectionClass($value['class']);
            $ins = $rfl->newInstanceArgs($args);

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

    /**
     * Adds a dependency to the collection
     *
     * @param String $name
     * @param Mixed $val
     * @return Warden
     */
    public function addDependency($name, $val)
    {
        $this->dependencies[$name] = $val;
        return $this;
    }

    /**
     * Fetches a known dependency from the array
     *
     * @return Mixed
     */
    public function getDependency($name)
    {
        if (!array_key_exists($name, $this->dependencies)) {
            throw new Exceptions\MissingDependencyException($name);
        }

        return $this->dependencies[$name];
    }

    /**
     * Returns all dependencies
     *
     * @return Array
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * Registers a governor class and creates its decorator
     *
     * @param \Warden\Governor\GovernorInterface $governor
     * @return Warden
     */
    public function addGovernor(GovernorInterface $governor)
    {
        $decorator = new GovernorDecorator(
            $this->dispatch,
            $governor->getService(),
            $governor->getAlias()
        );

        $this->governors[$governor->getAlias()] = ['decorator' => $decorator, 'handler' => $governor];

        return $this;
    }

    /**
     * Returns a governor class by its alias
     *
     * @param String $alias
     * @param String $key
     * @return \Warden\Governor\GovernorInterface
     */
    public function getGovernor($alias, $key = 'decorator')
    {
        if (!array_key_exists($alias, $this->governors)) {
            throw new Exceptions\GovernorNotFoundException($alias);
        }

        return $this->governors[$alias][$key];
    }

    /**
     * Returns the Handler class for a named governor
     *
     * @param String $alias
     * @return GovernorInterface
     */
    public function getGovernorHandler($alias)
    {
        return $this->getGovernor($alias, 'handler');
    }

} // END class Warden
