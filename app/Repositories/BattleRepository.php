<?php

namespace App\Repositories;

use App\Interfaces\BattleRepositoryInterface;
use App\Models\Battle;
use Illuminate\Support\Collection;

class BattleRepository implements BattleRepositoryInterface
{
    public function getAllBattles(): Collection
    {
        return Battle::all();
    }

    public function createBattle(array $newBattle): Battle
    {
        return Battle::create($newBattle);
    }

    public function getBattleById($battleId): Battle|null
    {
        return Battle::whereId($battleId)->first();
    }

    public function removeBattle($battleId): void
    {
        Battle::destroy($battleId);
    }
}
