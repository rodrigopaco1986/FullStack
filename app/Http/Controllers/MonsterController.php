<?php

namespace App\Http\Controllers;

use App\Services\MonsterService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MonsterController extends Controller
{
    /**
     * @var
     */
    protected $monsterService;

    /**
     * MonsterService constructor.
     *
     * @param  MonsterService  $monsterService
     */
    public function __construct(MonsterService $monsterService)
    {
        $this->monsterService = $monsterService;
    }

    /**
     * Get all monsters.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(
            [
                'data' => $this->monsterService->getAll(),
            ],
            Response::HTTP_OK
        );
    }

    /**
     * Create new monster.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $newMonster = $request->only([
            'name',
            'attack',
            'defense',
            'hp',
            'speed',
            'imageUrl',
        ]);

        return response()->json(
            [
                'data' => $this->monsterService->createMonster($newMonster),
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Get a monster.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $monsterId = $request->route('id');

        $result = $this->monsterService->getMonsterById($monsterId);

        if ($result) {
            return response()->json(
                [
                    'data' => $result,
                ],
                Response::HTTP_OK
            );
        }

        return response()->json(
            [
                'data' => null,
                'message' => 'The monster does not exists.',
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Update a monster.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $monsterId = $request->route('id');
        $newMonster = $request->only([
            'name',
            'attack',
            'defense',
            'hp',
            'speed',
            'imageUrl',
        ]);

        $result = $this->monsterService->getMonsterById($monsterId);

        if ($result) {
            $this->monsterService->updateMonster($monsterId, $newMonster);

            return response()->json('', Response::HTTP_OK);
        }

        return response()->json(
            [
                'data' => null,
                'message' => 'The monster does not exists.',
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Remove a monster.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function remove(Request $request): JsonResponse
    {
        $monsterId = $request->route('id');
        $result = $this->monsterService->getMonsterById($monsterId);

        if ($result) {
            $this->monsterService->removeMonster($monsterId);

            return response()->json('', Response::HTTP_NO_CONTENT);
        }

        return response()->json(
            [
                'data' => null,
                'message' => 'The monster does not exists.',
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    public function importCsv(Request $request): JsonResponse
    {
        $file = $request->file('file');
        if ($file) {
            $ext = $file->getClientOriginalExtension();
            if (! in_array($ext, ['csv'])) {
                return response()->json(['message' => 'File should be csv.'], Response::HTTP_BAD_REQUEST);
            }

            if (($handle = fopen($file, 'r')) !== false) {
                while (! feof($handle)) {
                    $rowData[] = fgetcsv($handle);
                }

                $csv_data = array_slice($rowData, 1, count($rowData));

                try {
                    $this->monsterService->importMonster($rowData, $csv_data);

                    return response()->json(['data' => 'Records were imported successfully.'], Response::HTTP_OK);
                } catch (QueryException $e) {
                    return response()->json(['message' => 'Incomplete data, check your file.'], Response::HTTP_BAD_REQUEST);
                } catch (Exception $e) {
                    return response()->json(['message' => 'Wrong data mapping.'], Response::HTTP_BAD_REQUEST);
                }
            }
        }

        return response()->json(['message' => 'Wrong data mapping.'], Response::HTTP_BAD_REQUEST);
    }
}
