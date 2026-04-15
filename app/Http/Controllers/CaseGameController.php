<?php

namespace App\Http\Controllers;

use App\Models\CaseGameRecord;
use App\Models\CaseTt10;
use App\Models\CaseTt3;
use App\Models\CaseTt5;
use App\Models\CaseTt7;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class CaseGameController extends Controller
{
    private const TZ = 'Asia/Ho_Chi_Minh';

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
        $date = $this->parseDate($dateInput) ?? Carbon::today(self::TZ);

        // Chuyển boundary ngày VN sang UTC để query đúng dữ liệu lưu UTC
        [$startUtc, $endUtc] = $this->utcBoundary($date);

        /** @var Collection<int, CaseGameRecord> $records */
        $records = $modelClass::query()
            ->whereBetween('game_datetime', [$startUtc, $endUtc])
            ->orderBy('id')
            ->get();

        $prevDatetime = $modelClass::query()
            ->where('game_datetime', '<', $startUtc)
            ->max('game_datetime');

        $nextDatetime = $modelClass::query()
            ->where('game_datetime', '>', $endUtc)
            ->min('game_datetime');

        // Parse UTC string rồi convert sang VN để lấy đúng ngày VN
        $prevDate = $prevDatetime
            ? Carbon::parse($prevDatetime)->setTimezone(self::TZ)->toDateString()
            : null;

        $nextDate = $nextDatetime
            ? Carbon::parse($nextDatetime)->setTimezone(self::TZ)->toDateString()
            : null;

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
     * Trả về các bản ghi trong ngày `date` (giờ VN) có id lớn hơn `after_id`.
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

        $date = Carbon::createFromFormat('Y-m-d', $validated['date'], self::TZ)->startOfDay();

        if (! $date->isToday(self::TZ)) {
            return response()->json(['records' => []]);
        }

        $afterId = (int) ($validated['after_id'] ?? 0);

        [$startUtc, $endUtc] = $this->utcBoundary($date);

        $rows = $modelClass::query()
            ->whereBetween('game_datetime', [$startUtc, $endUtc])
            ->where('id', '>', $afterId)
            ->orderBy('game_datetime')
            ->orderBy('id')
            ->get(['id', 'count', 'busted', 'dead_flg', 'game_datetime']);

        return response()->json([
            'records' => $rows->map(static fn (CaseGameRecord $r): array => [
                'id' => $r->id,
                'count' => $r->count,
                'busted' => $r->busted,
                'dead_flg' => $r->dead_flg,
                'game_datetime' => $r->game_datetime?->setTimezone(self::TZ)->format('Y-m-d H:i:s'),
            ])->values()->all(),
        ]);
    }

    public function exportPdf(Request $request, string $game): Response
    {
        $modelClass = self::GAME_MODELS[$game] ?? null;

        if ($modelClass === null) {
            abort(404);
        }

        $dateInput = $request->query('date');
        $date = $this->parseDate($dateInput) ?? Carbon::today(self::TZ);

        [$startUtc, $endUtc] = $this->utcBoundary($date);

        /** @var Collection<int, CaseGameRecord> $records */
        $records = $modelClass::query()
            ->whereBetween('game_datetime', [$startUtc, $endUtc])
            ->orderBy('id')
            ->get();

        $groupCount = 5;
        $rowsPerPage = 45;
        $recordsPerPage = $rowsPerPage * $groupCount;

        /** @var list<list<array{0: CaseGameRecord|null, 1: CaseGameRecord|null, 2: CaseGameRecord|null, 3: CaseGameRecord|null}>> $pages */
        $pages = [];

        foreach ($records->chunk($recordsPerPage)->values() as $pageRecords) {
            $groups = $pageRecords->chunk($rowsPerPage)->values();
            $maxRows = $groups->first()?->count() ?? 0;

            $rows = [];
            for ($i = 0; $i < $maxRows; $i++) {
                $row = [];
                for ($g = 0; $g < $groupCount; $g++) {
                    $group = $groups->get($g);
                    $row[] = $group instanceof Collection ? $group->values()->get($i) : null;
                }
                $rows[] = $row;
            }

            $pages[] = $rows;
        }

        $pdf = Pdf::loadView('cases.export-pdf', [
            'gameLabel' => self::GAME_LABELS[$game],
            'date' => $date,
            'pages' => $pages,
            'groupCount' => $groupCount,
            'totalRecords' => $records->count(),
        ]);

        $pdf->setPaper('a4', 'landscape');

        $filename = sprintf('%s_%s.pdf', $game, $date->format('Y-m-d'));

        return $pdf->download($filename);
    }

    /**
     * Trả về [start, end] theo UTC tương ứng với 00:00–23:59:59 của $date theo giờ VN.
     *
     * @return array{Carbon, Carbon}
     */
    private function utcBoundary(Carbon $date): array
    {
        $start = $date->copy()->startOfDay()->utc();
        $end = $date->copy()->endOfDay()->utc();

        return [$start, $end];
    }

    private function parseDate(?string $value): ?Carbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $value, self::TZ)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }
}
