<?php

use Warden\Warden;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Test case for the warden class
 *
 * @package Warden
 * @subpackage Tests
 * @author Dan Cox
 */
class WardenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Instance of the doctrine entity manager
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * An instance of Warden
     *
     * @var \Warden\Warden
     */
    protected $warden;

    /**
     * Set up test env
     *
     * @return void
     */
    public function setUp()
    {
        $setup = Setup::createAnnotationMetadataConfiguration(
            [ENTITIES],
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

        $this->warden = new Warden;
        $this->warden->addDependency('doctrine_em', $this->em);
        $this->warden->setup(CONFIG . 'warden.yml');
    }

    /**
     * Test that it can load settings from a YAML file
     *
     * @return void
     */
    public function test_it_loads_settings()
    {
        $params = $this->warden->getParams();

        $this->assertEquals('integer', $params->getType('request_time'));
        $this->assertEquals(0, $params->getDefault('request_memory'));
        $this->assertEquals(null, $params->getValue('request_time'));
    }

    /**
     * Test that it registers the specified collectors
     *
     * @return void
     */
    public function test_it_registers_specified_collectors()
    {
        $this->warden->start();
        $this->warden->stop();

        $params = $this->warden->getParams();

        $this->assertNotNull($params->getValue('request_memory'));
    }

    /**
     * Test that the results get analysed after the stop method is called
     *
     * @return void
     */
    public function test_results_are_analysed()
    {
        $this->setExpectedException('Warden\Exceptions\LimitExceededException');

        $this->warden->start();

        sleep(2);

        $this->warden->stop();
    }

    /**
     * Test that you can add and retrieve dependencies
     *
     * @return void
     */
    public function test_add_get_dependency()
    {
        $warden = new Warden;
        $warden->addDependency('test', new StdClass);

        $class = $warden->getDependency('test');
        $all = $warden->getDependencies();

        $this->assertEquals(new StdClass, $class);
        $this->assertEquals(['test' => new StdClass], $all);
    }

    /**
     * Test that it throws an exception when we hit the query limit
     *
     * @return void
     */
    public function test_it_throws_exception_on_query_limit()
    {
        $this->setExpectedException('Warden\Exceptions\LimitExceededException');

        $this->warden->start();

        // Le wild query in a loop appears
        for ($i = 0; $i < 10; $i++) {

            $result = $this->em
                           ->getRepository('Warden\Tests\Collector\Doctrine\Entities\Test')
                           ->findBy([], [], null, null);
        }

        $this->warden->stop();
    }

} // END class WardenTest extends \PHPUnit_Framework_TestCase
