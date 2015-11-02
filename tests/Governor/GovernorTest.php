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

} // END class GovernorTest extends \PHPUnit_Framework_TestCase
