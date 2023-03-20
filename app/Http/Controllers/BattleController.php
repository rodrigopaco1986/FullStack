<?php

namespace App\Http\Controllers;

use App\Services\BattleService;
use App\Services\MonsterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;

class BattleController extends Controller
{
    /**
     * @var
     */
    protected $battleService;

    /**
     * @var
     */
    protected $monsterService;

    /**
     * BattleService constructor.
     *
     * @param  BattleService  $battleService
     * @param  MonsterService  $monsterService
     */
    public function __construct(BattleService $battleService, MonsterService $monsterService)
    {
        $this->battleService = $battleService;
        $this->monsterService = $monsterService;
    }

    /**
     * Get all battles.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            [
                'data' => $this->battleService->getAll(),
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Create new battle.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $newBattle = $request->only([
            'monsterA',
            'monsterB',
        ]);

        $rules = [
            'monsterA' => 'required',
            'monsterB' => 'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if (! $validation->fails()) {
            $validated = $validation->validated();

            $battle = [
                'monsterA' => $this->monsterService->getMonsterById($validated['monsterA']),
                'monsterB' => $this->monsterService->getMonsterById($validated['monsterB']),
            ];

            if ($battle['monsterA'] && $battle['monsterB']) {
                return response()->json(
                    [
                        'data' => $this->battleService->createBattle($battle),
                    ],
                    Response::HTTP_CREATED
                );
            }

            return response()->json(
                [
                    'data' => null,
                    'message' => 'The battle does not exists.',
                ],
                Response::HTTP_NOT_FOUND
            );
        }

        return response()->json(
            [
                'data' => null,
                'message' => 'The battles are required.',
            ],
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * Remove a battle.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function remove(Request $request): JsonResponse
    {
        $battleId = $request->route('id');

        $result = $this->battleService->getBattleById($battleId);

        if ($result) {
            $this->battleService->removeBattle($battleId);

            return response()->json('', Response::HTTP_NO_CONTENT);
        }

        return response()->json(
            [
                'data' => null,
                'message' => 'The battle does not exists.',
            ],
            Response::HTTP_NOT_FOUND
        );
    }
}
