<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $count
 * @property int $busted
 * @property int|null $dead_flg
 * @property Carbon|null $game_datetime
 */
abstract class CaseGameRecord extends Model
{
    /** Bảng dùng khóa `id` int do hệ thống gán, không auto-increment. */
    public $incrementing = false;

    protected $keyType = 'int';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'count',
        'busted',
        'dead_flg',
        'game_datetime',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'game_datetime' => 'datetime',
        ];
    }
}
