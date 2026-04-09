<?php

namespace App\Http\Controllers;

use App\Models\CaseGameRecord;
use App\Models\CaseTt10;
use App\Models\CaseTt3;
use App\Models\CaseTt5;
use App\Models\CaseTt7;
use Illuminate\View\View;

class CaseGameController extends Controller
{
    /**
     * @var array<string, class-string<CaseGameRecord>>
     */
    private const GAME_MODELS = [
        'tt3' => CaseTt3::class,
        'tt5' => CaseTt5::class,
        'tt7' => CaseTt7::class,
        'tt10' => CaseTt10::class,
    ];

    /**
     * @var array<string, string>
     */
    private const GAME_LABELS = [
        'tt3' => 'TT3',
        'tt5' => 'TT5',
        'tt7' => 'TT7',
        'tt10' => 'TT10',
    ];

    public function show(string $game): View
    {
        $modelClass = self::GAME_MODELS[$game] ?? null;

        if ($modelClass === null) {
            abort(404);
        }

        $record = $modelClass::query()
            ->orderByDesc('game_datetime')
            ->orderByDesc('id')
            ->first();

        return view('cases.show', [
            'gameKey' => $game,
            'gameLabel' => self::GAME_LABELS[$game],
            'tableName' => (new $modelClass)->getTable(),
            'record' => $record,
        ]);
    }
}
