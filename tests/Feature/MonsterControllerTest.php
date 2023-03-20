<?php

namespace Tests\Feature;

use App\Models\Monster;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MonsterControllerTest extends TestCase
{
    use RefreshDatabase;

    private $monster;

    public function setUp(): void
    {
        parent::setUp();
        $this->monster = $this->createMonsters([
            'name' => 'My monster Test',
            'attack' => 20,
            'defense' => 40,
            'hp' => 70,
            'speed' => 10,
            'imageUrl' => '',
        ]);
    }

    public function test_should_get_all_monsters_correctly()
    {
        $this->createMonsters();
        $response = $this->getJson('api/monsters')->assertStatus(Response::HTTP_OK)->json('data');

        $this->assertEquals('My monster Test', $response[0]['name']);
    }

    public function test_should_get_a_single_monster_correctly()
    {
        $response = $this->getJson('api/monsters/1')->assertStatus(Response::HTTP_OK)->json('data');

        $this->assertEquals('My monster Test', $response['name']);
    }

    public function test_should_get_404_error_if_monster_does_not_exists()
    {
        $response = $this->getJson('api/monsters/999999')->assertStatus(Response::HTTP_NOT_FOUND)->json();

        $this->assertEquals('The monster does not exists.', $response['message']);
    }

    public function test_should_create_a_new_monster()
    {
        $monster = Monster::factory()->make();
        $response = $this->postJson('api/monsters', [
            'name' => $monster->name,
            'attack' => $monster->attack,
            'defense' => $monster->defense,
            'hp' => $monster->hp,
            'speed' => $monster->speed,
            'imageUrl' => $monster->imageUrl,
        ])->assertStatus(Response::HTTP_CREATED)->json('data');

        $this->assertEquals($monster->name, $response['name']);
    }

    public function test_should_update_a_monster_correctly()
    {
        $this->putJson('api/monsters/1', ['name' => 'updated name'])->assertStatus(Response::HTTP_OK)->json();
    }

    public function test_should_update_with_404_error_if_monster_does_not_exists()
    {
        $response = $this->putJson('api/monsters/999999', ['name' => 'updated name'])->assertStatus(Response::HTTP_NOT_FOUND)->json();

        $this->assertEquals('The monster does not exists.', $response['message']);
    }

    public function test_should_delete_a_monster_correctly()
    {
        $this->deleteJson('api/monsters/1')->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function test_should_delete_with_404_error_if_monster_does_not_exists()
    {
        $response = $this->deleteJson('api/monsters/999999')->assertStatus(Response::HTTP_NOT_FOUND)->json();

        $this->assertEquals('The monster does not exists.', $response['message']);
    }

    public function test_should_import_all_the_csv_objects_into_the_database_successfully()
    {
        $length = 3;
        $data = "name,attack,defense,hp,speed,imageUrl\n";

        for ($i = 0; $i < $length; $i++) {
            $monster = $this->createMonsters();
            $data .= implode(',', collect($monster)->toArray()).($i + 1 < $length ? "\n" : '');
        }

        $input = [
            'file' => UploadedFile::fake()
                ->createWithContent('file.csv', $data),
        ];

        $response = $this->postJson('api/monsters/import-csv', $input)
            ->assertStatus(Response::HTTP_OK)->json();

        $this->assertEquals('Records were imported successfully.', $response['data']);
    }

    public function test_should_fail_when_importing_csv_file_with_inexistent_columns()
    {
        $length = 3;
        $data = "attack,defense,hp,speed,imageUrl\n";

        for ($i = 0; $i < $length; $i++) {
            $monster = $this->createMonsters();
            $data .= implode(',', collect($monster)->toArray()).($i + 1 < $length ? "\n" : '');
        }

        $input = [
            'file' => UploadedFile::fake()
                ->createWithContent('file.csv', $data),
        ];

        $response = $this->postJson('api/monsters/import-csv', $input)
            ->assertStatus(Response::HTTP_BAD_REQUEST)->json();

        $this->assertEquals('Wrong data mapping.', $response['message']);
    }

    public function test_should_fail_when_importing_csv_file_with_wrong_or_inexistent_columns()
    {
        $length = 3;
        $data = "namessss,attack,defense,hp,speed,imageUrl\n";

        for ($i = 0; $i < $length; $i++) {
            $monster = $this->createMonsters();
            $data .= implode(',', collect($monster)->toArray()).($i + 1 < $length ? "\n" : '');
        }

        $input = [
            'file' => UploadedFile::fake()
                ->createWithContent('file.csv', $data),
        ];

        $response = $this->postJson('api/monsters/import-csv', $input)
            ->assertStatus(Response::HTTP_BAD_REQUEST)->json();

        $this->assertEquals('Incomplete data, check your file.', $response['message']);
    }

    public function test_should_fail_when_trying_import_a_file_with_different_extension()
    {
        $length = 3;
        $data = "name,attack,defense,hp,speed,imageUrl\n";

        for ($i = 0; $i < $length; $i++) {
            $monster = $this->createMonsters();
            $data .= implode(',', collect($monster)->toArray()).($i + 1 < $length ? "\n" : '');
        }

        $input = [
            'file' => UploadedFile::fake()
                ->createWithContent('file.doc', $data),
        ];

        $response = $this->postJson('api/monsters/import-csv', $input)
            ->assertStatus(Response::HTTP_BAD_REQUEST)->json();

        $this->assertEquals('File should be csv.', $response['message']);
    }

    public function test_should_fail_when_importing_none_file()
    {
        $response = $this->postJson('api/monsters/import-csv', [])
            ->assertStatus(Response::HTTP_BAD_REQUEST)->json();

        $this->assertEquals('Wrong data mapping.', $response['message']);
    }
}
