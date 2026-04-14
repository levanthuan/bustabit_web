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

        .header {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid #d97706;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            color: #92400e;
        }

        .header p {
            font-size: 10px;
            color: #78716c;
            margin-top: 2px;
        }

        .groups-wrapper {
            width: 100%;
        }

        .groups-table {
            width: 100%;
            border-collapse: collapse;
        }

        .groups-table > tbody > tr > td {
            width: 33.33%;
            vertical-align: top;
            padding: 0 4px;
        }

        .groups-table > tbody > tr > td:first-child { padding-left: 0; }
        .groups-table > tbody > tr > td:last-child { padding-right: 0; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background-color: #fef3c7;
            color: #92400e;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            padding: 4px 3px;
            border: 1px solid #d97706;
            text-align: center;
        }

        .data-table td {
            padding: 2.5px 3px;
            border: 1px solid #e5e7eb;
            text-align: center;
            font-size: 8.5px;
        }

        .data-table tr:nth-child(even) td {
            background-color: #fafaf9;
        }

        .dead-row td {
            background-color: #ffe4e6 !important;
            font-weight: bold;
        }

        .dead-badge {
            display: inline-block;
            background-color: #e11d48;
            color: #fff;
            font-size: 7px;
            font-weight: bold;
            padding: 1px 4px;
            border-radius: 3px;
        }

        .normal-badge {
            color: #a1a1aa;
            font-size: 7.5px;
        }

        .footer {
            margin-top: 8px;
            padding-top: 4px;
            border-top: 1px solid #e5e7eb;
            text-align: right;
            font-size: 7.5px;
            color: #a1a1aa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $gameLabel }} – {{ $date->format('d/m/Y') }}</h1>
        <p>Tổng: {{ $totalRecords }} bản ghi</p>
    </div>

    <div class="groups-wrapper">
        <table class="groups-table">
            <tbody>
                <tr>
                    @for ($i = 0; $i < 3; $i++)
                        <td>
                            @if (isset($groups[$i]) && $groups[$i]->isNotEmpty())
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Đếm</th>
                                            <th>Giá trị</th>
                                            <th>Cầu chết</th>
                                            <th>Thời gian</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($groups[$i] as $record)
                                            @php $isDead = (int) $record->dead_flg === 1; @endphp
                                            <tr @if ($isDead) class="dead-row" @endif>
                                                <td>{{ $record->count ?? '—' }}</td>
                                                <td>{{ $record->busted }}</td>
                                                <td>
                                                    @if ($record->dead_flg === null)
                                                        <span class="normal-badge">—</span>
                                                    @elseif ($isDead)
                                                        <span class="dead-badge">★ Dead</span>
                                                    @else
                                                        <span class="normal-badge">0</span>
                                                    @endif
                                                </td>
                                                <td>{{ $record->game_datetime?->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s') ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </td>
                    @endfor
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        Xuất lúc {{ now()->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
