<?php

namespace Warden\Tests\Collector\Doctrine\Entities;

/**
 * A test entity
 *
 * @package Warden
 * @subpackage Tests
 * @Entity
 */
class Test
{
    /**
     * The identifier
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     * @var Integer
     */
    public $id;

    /**
     * Title attr
     *
     * @Column(type="string")
     * @var String
     */
    public $title;

    /**
     * Just a string
     *
     * @Column(type="string")
     * @var String
     */
    public $test;

} // END class Test
