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

} // END class GovernorTest extends \PHPUnit_Framework_TestCase
