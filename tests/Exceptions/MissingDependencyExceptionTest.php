<?php

use Warden\Exceptions\MissingDependencyException;

/**
 * Test case for the missing dependency exception class
 *
 * @package Warden
 * @subpackage Tests
 * @author Dan Cox
 */
class MissingDependencyExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test firing the exception
     *
     * @return void
     */
    public function test_fire()
    {
        try {

            throw new MissingDependencyException('Test');

        } catch (MissingDependencyException $e) {
            $this->assertEquals('Test', $e->getDependency());
            return;
        }

        $this->fail('Exception was not thrown/caught');
    }
} // END class MissingDependencyExceptionTest extends \PHPUnit_Framework_TestCase
