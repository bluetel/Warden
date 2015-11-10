<?php

namespace Warden\Tests\Governor\Governors;

/**
 * A test service that does nilp
 *
 * @package Warden
 * @author Dan Cox
 */
class TestService
{
    /**
     * Just a test property
     *
     * @var String
     */
    public $var;

    /**
     * Just a test method
     *
     * @return String
     */
    public function getAValue()
    {
        return 'test';
    }

} // END class TestService

