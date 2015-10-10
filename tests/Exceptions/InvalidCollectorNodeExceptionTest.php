<?php

use Warden\Exceptions\InvalidCollectorNodeException;

/**
 * Test case for the invalid collector node exception
 *
 * @package Warden
 * @subpackage Tests
 * @author Dan Cox
 */
class InvalidCollectorNodeExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test throwing exception
     *
     * @return void
     */
    public function test_fire()
    {
        try {

            throw new InvalidCollectorNodeException('test');

        } catch (InvalidCollectorNodeException $e) {

            $this->assertEquals('test', $e->getNode());
            return;
        }

        $this->fail('Exception was not caught');
    }

} // END class InvalidCollectorNodeExceptionTest extends \PHPUnit_Framework_TestCase
