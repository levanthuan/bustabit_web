<?php

namespace App\Http\Controllers;

use App\Models\CaseGameRecord;
use App\Models\CaseTt10;
use App\Models\CaseTt3;
use App\Models\CaseTt5;
use App\Models\CaseTt7;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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

    public function show(Request $request, string $game): View
    {
        $modelClass = self::GAME_MODELS[$game] ?? null;

        if ($modelClass === null) {
            abort(404);
        }

        $dateInput = $request->query('date');
        $date = $this->parseDate($dateInput) ?? Carbon::today();

        /** @var Collection<int, CaseGameRecord> $records */
        $records = $modelClass::query()
            ->whereDate('game_datetime', $date)
            ->orderBy('game_datetime')
            ->orderBy('id')
            ->get();

        $prevDatetime = $modelClass::query()
            ->whereDate('game_datetime', '<', $date)
            ->max('game_datetime');

        $nextDatetime = $modelClass::query()
            ->whereDate('game_datetime', '>', $date)
            ->min('game_datetime');

        $prevDate = $prevDatetime ? Carbon::parse($prevDatetime)->toDateString() : null;
        $nextDate = $nextDatetime ? Carbon::parse($nextDatetime)->toDateString() : null;

        return view('cases.show', [
            'gameKey' => $game,
            'gameLabel' => self::GAME_LABELS[$game],
            'records' => $records,
            'date' => $date,
            'prevDate' => $prevDate,
            'nextDate' => $nextDate,
        ]);
    }

    /**
     * Trả về các bản ghi trong ngày `date` có id lớn hơn `after_id` (dùng poll realtime cho ngày hôm nay).
     */
    public function recordsSince(Request $request, string $game): JsonResponse
    {
        $modelClass = self::GAME_MODELS[$game] ?? null;

        if ($modelClass === null) {
            abort(404);
        }

        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'after_id' => ['sometimes', 'integer', 'min:0'],
        ]);

        $date = Carbon::createFromFormat('Y-m-d', $validated['date'])->startOfDay();

        if (! $date->isToday()) {
            return response()->json(['records' => []]);
        }

        $afterId = (int) ($validated['after_id'] ?? 0);

        $rows = $modelClass::query()
            ->whereDate('game_datetime', $date)
            ->where('id', '>', $afterId)
            ->orderBy('game_datetime')
            ->orderBy('id')
            ->get(['id', 'count', 'busted', 'dead_flg']);

        return response()->json([
            'records' => $rows->map(static fn (CaseGameRecord $r): array => [
                'id' => $r->id,
                'count' => $r->count,
                'busted' => $r->busted,
                'dead_flg' => $r->dead_flg,
            ])->values()->all(),
        ]);
    }

    private function parseDate(?string $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }
}
