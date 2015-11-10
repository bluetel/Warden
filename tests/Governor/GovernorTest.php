<?php

use Warden\Warden;
use Warden\Tests\Governor\Governors\TestGovernor;

/**
 * Test case for the governor classes
 *
 * @package Warden
 * @subpackage Tests
 * @author Dan Cox
 */
class GovernorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that you can register and return a governor
     *
     * @return void
     */
    public function test_register_and_get()
    {
        $warden = new Warden;
        $warden->addGovernor(new TestGovernor);
        $warden->start();

        $decorator = $warden->getGovernor('testGov');

        $this->assertEquals('test', $decorator->getAValue());
    }

    /**
     * Test that Warden throws an exception when we access
     * a class that isnt registered
     *
     * @return void
     */
    public function test_access_to_unknown_governor()
    {
        $this->setExpectedException('Warden\Exceptions\GovernorNotFoundException');

        $warden = new Warden;
        $warden->getGovernor('test');
    }

    /**
     * Test that the governor classes can access the param bag
     *
     * @return void
     */
    public function test_governor_using_parambag()
    {
        $warden = new Warden;
        $warden->addGovernor(new TestGovernor);
        $warden->start();

        $d = $warden->getGovernor('testGov');

        $d->getAValue();

        $params = $warden->getParams();

        $this->assertEquals(52, $params->getValue('governor_test'));
    }

} // END class GovernorTest extends \PHPUnit_Framework_TestCase
