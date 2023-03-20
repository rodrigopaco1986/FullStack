<?php

namespace App\Services;

use App\Models\Monster;
use App\Repositories\MonsterRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class MonsterService
{
    /**
     * @var
     */
    protected $monsterRepository;

    /**
     * MonsterService constructor.
     *
     * @param  MonsterRepository  $monsterRepository
     */
    public function __construct(MonsterRepository $monsterRepository)
    {
        $this->monsterRepository = $monsterRepository;
    }

    /**
     * Get all monsters.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->monsterRepository->getAllMonsters();
    }

    /**
     * Get a monster.
     *
     * @param  mixed  $monsterId
     * @return Monster|JsonResponse|null
     */
    public function getMonsterById($monsterId): Monster|JsonResponse|null
    {
        return $this->monsterRepository->getMonsterById($monsterId);
    }

    /**
     * Create a monster.
     *
     * @param  mixed  $newMonster
     * @return Monster|JsonResponse
     */
    public function createMonster($newMonster): Monster|JsonResponse
    {
        return $this->monsterRepository->createMonster($newMonster);
    }

    /**
     * Update a monster.
     *
     * @param  mixed  $monsterId
     * @param  mixed  $newMonster
     * @return void
     */
    public function updateMonster($monsterId, $newMonster): void
    {
        $this->monsterRepository->updateMonster($monsterId, $newMonster);
    }

    /**
     * Remove a monster.
     *
     * @param  mixed  $monsterId
     * @return void
     */
    public function removeMonster($monsterId): void
    {
        $this->monsterRepository->removeMonster($monsterId);
    }

    /**
     * Import csv to monster.
     *
     * @param  mixed  $data
     * @param  mixed  $csv_data
     * @return void
     */
    public function importMonster($data, $csv_data): void
    {
        foreach ($csv_data as $row) {
            $inserted_data = [
                $data[0][0] => $row[0],
                $data[0][1] => $row[1],
                $data[0][2] => $row[2],
                $data[0][3] => $row[3],
                $data[0][4] => $row[4],
                $data[0][5] => $row[5],
            ];

            Monster::create($inserted_data);
        }
    }
}
