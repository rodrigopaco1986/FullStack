<?php

namespace App\Services;

use App\Models\Battle;
use App\Repositories\BattleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class BattleService
{
    /**
     * @var
     */
    protected $battleRepository;

    /**
     * BattleService constructor.
     *
     * @param  BattleRepository  $battleRepository
     */
    public function __construct(BattleRepository $battleRepository)
    {
        $this->battleRepository = $battleRepository;
    }

    /**
     * Get all battles.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->battleRepository->getAllBattles();
    }

    /**
     * Get a battle.
     *
     * @param  mixed  $battleId
     * @return Battle|JsonResponse|null
     */
    public function getBattleById($battleId): Battle|JsonResponse|null
    {
        return $this->battleRepository->getBattleById($battleId);
    }

    /**
     * Create a battle.
     *
     * @param  mixed  $newBattle
     * @return Battle|JsonResponse
     */
    public function createBattle($newBattle): Battle|JsonResponse
    {
        $winner = $turns = $waiting = null;
        [$monsterA, $monsterB] = array_values($newBattle);

        $battle = [
            $monsterA->id => $monsterA,
            $monsterB->id => $monsterB,
        ];

        while ($winner == null) {
            if ($monsterA->speed == $monsterB->speed) {
                $turns = ($monsterA->attack >= $monsterB->attack ? $monsterA->id : $monsterB->id);
                $waiting = ($monsterA->attack >= $monsterB->attack ? $monsterB->id : $monsterA->id);
            } else {
                $turns = ($monsterA->speed >= $monsterB->speed ? $monsterA->id : $monsterB->id);
                $waiting = ($monsterA->speed >= $monsterB->speed ? $monsterB->id : $monsterA->id);
            }

            $attack = $battle[$turns]->attack;
            $damage = ($attack <= $battle[$waiting]->defense ? 1 : $attack - $battle[$waiting]->defense);
            $hp = $battle[$waiting]->hp - $damage;

            $battle[$waiting]->hp = $hp;

            if ($hp <= 0) {
                $winner = $battle[$turns];
            } else {
                $currentTurn = $turns;
                $turns = $waiting;
                $waiting = $currentTurn;
            }
        }

        $newBattle['winner'] = $winner;

        return $this->battleRepository->createBattle($newBattle);
    }

    /**
     * Remove a battle.
     *
     * @param  mixed  $battleId
     * @return void
     */
    public function removeBattle($battleId): void
    {
        $this->battleRepository->removeBattle($battleId);
    }
}
