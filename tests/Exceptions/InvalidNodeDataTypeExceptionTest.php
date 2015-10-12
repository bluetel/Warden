<?php

use Warden\Exceptions\InvalidNodeDataTypeException;

/**
 * Test case for the invalid node data type exception
 *
 * @package Warden
 * @subpackage Tests
 * @author Dan Cox
 */
class InvalidNodeDataTypeExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test throwing and catching exception
     *
     * @return void
     */
    public function test_throw()
    {
        try {

            throw new InvalidNodeDataTypeException('integer', 'string', 'request_time');

        } catch (InvalidNodeDataTypeException $e) {

            $this->assertEquals('integer', $e->getType());
            $this->assertEquals('string', $e->getAllowed());
            $this->assertEquals('request_time', $e->getNode());
            return;
        }

        $this->fail('Exception could not be thrown/caught');
    }

} // END class InvalidNodeDataTypeExceptionTest extends \PHPUnit_Framework_TestCase
