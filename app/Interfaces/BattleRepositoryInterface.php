<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BattleRepositoryInterface
{
    public function getAllBattles(): Collection;

    public function createBattle(array $newBattle): Model;

    public function getBattleById($battleId): Model|null;

    public function removeBattle($battleId): void;
}
