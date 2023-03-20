<?php

namespace Tests;

use App\Models\Battle;
use App\Models\Monster;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function createMonsters($args = [])
    {
        return Monster::factory()->create($args);
    }

    public function createBattles($args = [])
    {
        return Battle::factory()->create($args);
    }
}
