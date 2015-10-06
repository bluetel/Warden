<?php

use Warden\Collector\CollectorParamBag;

/**
 * Test case for the collector param bag
 *
 * @package Warden
 * @subpackage Tests
 * @author Dan Cox
 */
class CollectorParamBagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that it allows me to add a collector information node
     * And subsequently allows me to set and get
     *
     * @return void
     * @author Dan Cox
     */
    public function test_can_add_and_get_set()
    {
        $parambag = new CollectorParamBag;
        $parambag->add('request_time', ['type' => 'integer', 'default' => 100, 'limit' => 5, 'value' => '']);

        $this->assertEquals('integer', $parambag->getType('request_time'));
        $this->assertEquals(100, $parambag->getDefault('request_time'));
        $this->assertEquals(5, $parambag->getLimit('request_time'));
        $this->assertEquals('', $parambag->request_time);

        $parambag->request_time = 500;
        $parambag->setLimit('request_time', 15);

        $this->assertEquals(500, $parambag->request_time);
        $this->assertEquals(15, $parambag->getLimit('request_time'));

        $parambag->setValue('request_time', 10);

        $this->assertEquals(10, $parambag->getValue('request_time'));
        $this->assertEquals(1, count($parambag->all()));
    }

} // END class CollectorParamBagTest extends \PHPUnit_Framework_TestCase
