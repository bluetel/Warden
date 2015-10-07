<?php

use Warden\Exceptions\LimitExceededException;

/**
 * The test case for the limit exceeded exception
 *
 * @package Warden
 * @subpackage Tests
 * @author Dan Cox
 */
class LimitExceededExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test firing exception
     *
     * @return void
     * @author Dan Cox
     */
    public function test_fire_exception()
    {
        try {

            throw new LimitExceededException(10, 5, 'request_time');

        } catch (LimitExceededException $e) {
            $this->assertEquals(10, $e->getActual());
            $this->assertEquals(5, $e->getLimit());
            $this->assertEquals('request_time', $e->getName());
            return;
        }

        $this->fail('The exception did not fire');
    }

} // END class LimitExceededExceptionTest extends \PHPUnit_Framework_TestCase
