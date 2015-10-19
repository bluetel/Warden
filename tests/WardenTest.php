<?php

use Warden\Warden;

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
     * Test that it can load settings from a YAML file
     *
     * @return void
     */
    public function test_it_loads_settings()
    {
        $warden = new Warden;
        $warden->setup(__DIR__ . '/config/warden.yml');

        $params = $warden->getParams();

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
        $warden = new Warden;
        $warden->setup(__DIR__ . '/config/warden.yml');

        $warden->start();
        $warden->stop();

        $params = $warden->getParams();

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

        $warden = new Warden;
        $warden->setup(__DIR__ . '/config/warden.yml');

        $warden->start();

        sleep(2);

        $warden->stop();
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

} // END class WardenTest extends \PHPUnit_Framework_TestCase
