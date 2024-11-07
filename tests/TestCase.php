<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Database\Seeders\TestDatabaseSeeder;

abstract class TestCase extends BaseTestCase
{
        /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /**
     * Run a specific seeder before each test.
     *
     * @var string
     */
    protected $seeder = TestDatabaseSeeder::class;
}
