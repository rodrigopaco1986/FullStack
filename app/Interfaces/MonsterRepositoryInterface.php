<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface MonsterRepositoryInterface
{
    public function getAllMonsters(): Collection;

    public function getMonsterById($monsterId): Model|null;

    public function createMonster(array $newMonster): Model;

    public function updateMonster($monsterId, array $newMonster): void;

    public function removeMonster($monsterId): void;
}
