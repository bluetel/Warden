<?php

use Warden\Exceptions\GovernorNotFoundException;

/**
 * Test Case for the governor not found exception
 *
 * @package Warden
 * @subpackage Tests
 * @author Dan Cox
 */
class GovernorNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test firing the exception
     *
     * @return void
     */
    public function test_fire()
    {
        try {

            throw new GovernorNotFoundException('test');

        } catch (GovernorNotFoundException $e) {
            $this->assertEquals('test', $e->getName());
            return;
        }

        $this->fail("Exception not fired/caught...");
    }

} // END class GovernorNotFoundExceptionTest extends \PHPUnit_Framework_TestCase
