<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{{ $gameLabel }} – {{ $date->format('d/m/Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #1a1a1a;
        }

        .page {
            page-break-after: always;
            padding-left: 20px;
            padding-right: 20px;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        .header {
            text-align: center;
            margin-bottom: 6px;
            padding-bottom: 4px;
            border-bottom: 2px solid #d97706;
        }

        .header h1 {
            font-size: 14px;
            font-weight: bold;
            color: #92400e;
        }

        .header p {
            font-size: 9px;
            color: #78716c;
            margin-top: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #fef3c7;
            color: #92400e;
            font-size: 6.5px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 2px 1px;
            border: 1px solid #d97706;
            text-align: center;
        }

        td {
            padding: 1.5px 1px;
            border: 1px solid #e5e7eb;
            text-align: center;
            font-size: 7px;
        }

        .sep {
            border-left: 2px solid #d97706;
        }

        @for ($g = 1; $g <= $groupCount; $g++)
        @php $start = ($g - 1) * 4 + 1; $end = $g * 4; @endphp
        .dead-row-{{ $g }} td:nth-child(n+{{ $start }}):nth-child(-n+{{ $end }}) {
            background-color: #ffe4e6;
            font-weight: bold;
        }
        @endfor

        .empty-cell {
            color: #d4d4d8;
        }

        .dead-badge {
            display: inline-block;
            background-color: #e11d48;
            color: #fff;
            font-size: 6.5px;
            font-weight: bold;
            padding: 1px 3px;
            border-radius: 3px;
        }

        .normal-badge {
            color: #a1a1aa;
            font-size: 7px;
        }

        .footer {
            margin-top: 4px;
            text-align: right;
            font-size: 7px;
            color: #a1a1aa;
        }
    </style>
</head>
<body>
    @foreach ($pages as $pageIndex => $rows)
        <div class="page">
            <div class="header">
                <h1>{{ $gameLabel }} – {{ $date->format('d/m/Y') }}</h1>
                <p>Tổng: {{ $totalRecords }} bản ghi · Trang {{ $pageIndex + 1 }}/{{ count($pages) }}</p>
            </div>

            <table>
                <thead>
                    <tr>
                        @for ($g = 0; $g < $groupCount; $g++)
                            <th @if ($g > 0) class="sep" @endif>Đếm</th>
                            <th>Giá trị</th>
                            <th>Cầu chết</th>
                            <th>Giờ</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        @php
                            $classes = [];
                            for ($g = 0; $g < $groupCount; $g++) {
                                if (isset($row[$g]) && (int) $row[$g]->dead_flg === 1) {
                                    $classes[] = 'dead-row-' . ($g + 1);
                                }
                            }
                        @endphp
                        <tr class="{{ implode(' ', $classes) }}">
                            @for ($g = 0; $g < $groupCount; $g++)
                                @php $rec = $row[$g] ?? null; @endphp
                                @if ($rec)
                                    <td @if ($g > 0) class="sep" @endif>{{ $rec->count ?? '—' }}</td>
                                    <td>{{ $rec->busted }}</td>
                                    <td>
                                        @if ($rec->dead_flg === null)
                                            <span class="normal-badge">—</span>
                                        @elseif ((int) $rec->dead_flg === 1)
                                            <span class="dead-badge">★ Dead</span>
                                        @else
                                            <span class="normal-badge">0</span>
                                        @endif
                                    </td>
                                    <td>{{ $rec->game_datetime?->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s') ?? '—' }}</td>
                                @else
                                    <td @if ($g > 0) class="sep empty-cell" @else class="empty-cell" @endif>—</td>
                                    <td class="empty-cell">—</td>
                                    <td class="empty-cell">—</td>
                                    <td class="empty-cell">—</td>
                                @endif
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="footer">
                Xuất lúc {{ now()->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }}
            </div>
        </div>
    @endforeach
</body>
</html>
