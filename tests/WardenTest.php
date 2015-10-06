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
     * @author Dan Cox
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

} // END class WardenTest extends \PHPUnit_Framework_TestCase
