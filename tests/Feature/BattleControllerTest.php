<?php

namespace Tests\Feature;

use App\Models\Monster;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class BattleControllerTest extends TestCase
{
    use RefreshDatabase;

    private $battle;

    private $monster1;

    private $monster2;

    private $monster3;

    private $monster4;

    private $monster5;

    private $monster6;

    private $monster7;

    public function setUp(): void
    {
        parent::setUp();
        $this->createBattles();
    }

    public function test_should_get_all_battles_correctly()
    {
        $this->createBattles();

        $response = $this->getJson('api/battles')->assertStatus(Response::HTTP_OK)->json('data');

        $this->assertEquals(2, count($response));
    }

    public function test_should_create_a_battle_with_a_bad_request_response_if_one_parameter_is_null()
    {
        $monsterA = Monster::first()->id;
        $monsterB = null;

        $input = [
            'monsterA' => $monsterA,
            'monsterB' => $monsterB,
        ];

        $response = $this->postJson('api/battles', $input)
            ->assertStatus(Response::HTTP_BAD_REQUEST)->json();

        $this->assertEquals('The battles are required.', $response['message']);
    }

    public function test_should_create_a_battle_with_404_error_if_one_parameter_has_a_monster_id_does_not_exists()
    {
        $monsterA = Monster::first()->id;
        $monsterB = 999999;

        $input = [
            'monsterA' => $monsterA,
            'monsterB' => $monsterB,
        ];

        $response = $this->postJson('api/battles', $input)
            ->assertStatus(Response::HTTP_NOT_FOUND)->json();

        $this->assertEquals('The battle does not exists.', $response['message']);
    }

    public function test_should_create_battle_correctly_with_monsterA_winning()
    {
        $monsterA = $this->createMonsters([
            'name' => 'My monster Test A',
            'attack' => 20,
            'defense' => 40,
            'hp' => 70,
            'speed' => 90,
            'imageUrl' => '',
        ]);
        $monsterB = $this->createMonsters([
            'name' => 'My monster Test B',
            'attack' => 20,
            'defense' => 40,
            'hp' => 70,
            'speed' => 10,
            'imageUrl' => '',
        ]);

        $input = [
            'monsterA' => $monsterA->id,
            'monsterB' => $monsterB->id,
        ];

        $response = $this->postJson('api/battles', $input)
            ->assertStatus(Response::HTTP_CREATED)->json('data');

        $this->assertEquals($monsterA['name'], $response['winner']['name']);
    }

    public function test_should_create_battle_correctly_with_monsterB_winning()
    {
        $monsterA = $this->createMonsters([
            'name' => 'My monster Test A',
            'attack' => 20,
            'defense' => 40,
            'hp' => 70,
            'speed' => 10,
            'imageUrl' => '',
        ]);
        $monsterB = $this->createMonsters([
            'name' => 'My monster Test B',
            'attack' => 20,
            'defense' => 40,
            'hp' => 70,
            'speed' => 90,
            'imageUrl' => '',
        ]);

        $input = [
            'monsterA' => $monsterA->id,
            'monsterB' => $monsterB->id,
        ];

        $response = $this->postJson('api/battles', $input)
            ->assertStatus(Response::HTTP_CREATED)->json('data');

        $this->assertEquals($monsterB['name'], $response['winner']['name']);
    }

    public function test_should_create_battle_correctly_with_monsterA_winning_if_theirs_speeds_same_and_monsterA_has_higher_attack()
    {
        $monsterA = $this->createMonsters([
            'name' => 'My monster Test A',
            'attack' => 50,
            'defense' => 40,
            'hp' => 70,
            'speed' => 30,
            'imageUrl' => '',
        ]);
        $monsterB = $this->createMonsters([
            'name' => 'My monster Test B',
            'attack' => 20,
            'defense' => 40,
            'hp' => 70,
            'speed' => 30,
            'imageUrl' => '',
        ]);

        $input = [
            'monsterA' => $monsterA->id,
            'monsterB' => $monsterB->id,
        ];

        $response = $this->postJson('api/battles', $input)
            ->assertStatus(Response::HTTP_CREATED)->json('data');

        $this->assertEquals($monsterA['name'], $response['winner']['name']);
    }

    public function test_should_create_battle_correctly_with_monsterB_winning_if_theirs_speeds_same_and_monsterB_has_higher_attack()
    {
        $monsterA = $this->createMonsters([
            'name' => 'My monster Test A',
            'attack' => 20,
            'defense' => 40,
            'hp' => 70,
            'speed' => 30,
            'imageUrl' => '',
        ]);
        $monsterB = $this->createMonsters([
            'name' => 'My monster Test B',
            'attack' => 50,
            'defense' => 40,
            'hp' => 70,
            'speed' => 30,
            'imageUrl' => '',
        ]);

        $input = [
            'monsterA' => $monsterA->id,
            'monsterB' => $monsterB->id,
        ];

        $response = $this->postJson('api/battles', $input)
            ->assertStatus(Response::HTTP_CREATED)->json('data');

        $this->assertEquals($monsterB['name'], $response['winner']['name']);
    }

    public function test_should_create_battle_correctly_with_monsterA_winning_if_theirs_defense_same_and_monsterA_has_higher_speed()
    {
        $monsterA = $this->createMonsters([
            'name' => 'My monster Test A',
            'attack' => 20,
            'defense' => 40,
            'hp' => 70,
            'speed' => 50,
            'imageUrl' => '',
        ]);
        $monsterB = $this->createMonsters([
            'name' => 'My monster Test B',
            'attack' => 20,
            'defense' => 40,
            'hp' => 70,
            'speed' => 30,
            'imageUrl' => '',
        ]);

        $input = [
            'monsterA' => $monsterA->id,
            'monsterB' => $monsterB->id,
        ];

        $response = $this->postJson('api/battles', $input)
            ->assertStatus(Response::HTTP_CREATED)->json('data');

        $this->assertEquals($monsterA['name'], $response['winner']['name']);
    }

    public function test_should_delete_a_battle_correctly()
    {
        $this->deleteJson('api/battles/1')->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_should_delete_with_404_error_if_battle_does_not_exists()
    {
        $response = $this->deleteJson('api/battles/999999')->assertStatus(Response::HTTP_NOT_FOUND)->json();

        $this->assertEquals('The battle does not exists.', $response['message']);
    }
}
