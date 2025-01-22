<?php

namespace seeders;

use Faker\Factory;
use Faker\Generator;


abstract class Seeders
{
    protected Generator $faker;

    static public array $seeds = [
        UsersSeeder::class
    ];
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    abstract public function run(): void;
}