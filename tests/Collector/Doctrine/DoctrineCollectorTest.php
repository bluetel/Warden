<?php

use Warden\Collector\Doctrine\DoctrineQueryCollector;
use Warden\Tests\Collector\Doctrine\Entities\Test;
use Warden\Collector\CollectorParamBag;
use Warden\Analyser;
use Warden\WardenEvents;
use Warden\Events\StartEvent;
use Warden\Events\StopEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Test case for the doctrine collector
 *
 * @package Warden
 * @subpackage Tests
 * @author Dan Cox
 */
class DoctrineCollectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Instance of the doctrine entity manager
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Instance of the schema tool
     *
     * @var \Doctrine\ORM\Tools\SchemaTool
     */
    protected $schema;

    /**
     * Instance of the Events dispatcher
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Instance of the param bag
     *
     * @var \Warden\Collector\CollectorParamBag
     */
    protected $params;

    /**
     * The start event
     *
     * @var \Warden\Events\StartEvent
     */
    protected $start;

    /**
     * The stop event
     *
     * @var \Warden\Events\StopEvent
     */
    protected $stop;

    /**
     * Set up test env
     *
     * @return void
     */
    public function setUp()
    {
        $this->eventDispatcher = new EventDispatcher;
        $this->params = new CollectorParamBag;

        $setup = Setup::createAnnotationMetadataConfiguration(
            [__DIR__ . '/Entities'],
            true
        );

        $this->em = EntityManager::create(
            [
                'driver'        => 'pdo_mysql',
                'user'          => 'user',
                'password'      => '',
                'dbname'        => 'warden'
            ],
            $setup
        );

        // Setup Param Bag
        $this->params->add('query_limit', [
            'type'          => 'integer',
            'default'       => 5,
            'limit'         => 5,
            'value'         => null,
            'expression'    => 'value >= limit'
        ]);

        // Sync the schema
        $this->schema = new SchemaTool($this->em);
        $this->schema->updateSchema(
            $this->em->getMetadataFactory()->getAllMetadata(),
            true
        );

        // Events
        $this->start = new StartEvent;
        $this->stop = new StopEvent($this->params);
    }

    /**
     * Test that it can count queries
     *
     * @return void
     */
    public function test_it_can_count_queries()
    {
        $collector = new DoctrineQueryCollector($this->em);
        $collector->register($this->eventDispatcher);

        // Start event
        $this->eventDispatcher->dispatch(WardenEvents::WARDEN_START, $this->start);

        $test = new Test;
        $test->title = 'test';
        $test->test = 'foo';

        $this->em->persist($test);
        $this->em->flush();

        $result = $this->em
                        ->getRepository('Warden\Tests\Collector\Doctrine\Entities\Test')
                        ->findOneBy(['test' => 'foo']);

        // Stop event
        $event = $this->eventDispatcher->dispatch(WardenEvents::WARDEN_END, $this->stop);

        /**
         * Why is this 4? Because Doctrine performs 1 query to start a transaction,
         * 1 to insert, 1 to commit, and 1 to search
         */
        $this->assertEquals(4, $event->params->getValue('query_limit'));
    }
} // END class DoctrineCollectorTest extends \PHPUnit_Framework_TestCase
