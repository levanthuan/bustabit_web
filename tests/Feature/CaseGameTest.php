<?php

use App\Models\CaseTt3;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('khách không xem được trang case', function () {
    $response = $this->get(route('case.show', 'tt3'));

    $response->assertRedirect(route('login'));
});

test('user xem được trang TT3 và thấy count busted dead_flg', function () {
    $user = User::factory()->create();

    CaseTt3::query()->create([
        'id' => 1,
        'count' => 42,
        'busted' => 7,
        'dead_flg' => 1,
        'game_datetime' => now(),
    ]);

    $response = $this->actingAs($user)->get(route('case.show', 'tt3'));

    $response->assertOk();
    $response->assertSee('TT3', false);
    $response->assertSee('42', false);
    $response->assertSee('7', false);
    $response->assertSee('1', false);
});

test('game key không hợp lệ trả 404', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(url('/tt/invalid'));

    $response->assertNotFound();
});

test('khách không gọi được API poll records', function () {
    $url = route('case.records.since', ['game' => 'tt3']).'?'.http_build_query([
        'date' => now()->toDateString(),
        'after_id' => 0,
    ]);

    $response = $this->getJson($url);

    $response->assertUnauthorized();
});

test('API poll trả bản ghi mới sau after_id trong ngày hôm nay', function () {
    $user = User::factory()->create();

    CaseTt3::query()->create([
        'id' => 10,
        'count' => 1,
        'busted' => 2,
        'dead_flg' => 0,
        'game_datetime' => now(),
    ]);

    CaseTt3::query()->create([
        'id' => 20,
        'count' => 3,
        'busted' => 4,
        'dead_flg' => 1,
        'game_datetime' => now(),
    ]);

    $url = route('case.records.since', ['game' => 'tt3']).'?'.http_build_query([
        'date' => now()->toDateString(),
        'after_id' => 10,
    ]);

    $response = $this->actingAs($user)->getJson($url);

    $response->assertOk();
    $response->assertJsonPath('records.0.id', 20);
    $response->assertJsonPath('records.0.count', 3);
    $response->assertJsonPath('records.0.dead_flg', 1);
});

test('API poll với ngày không phải hôm nay trả rỗng', function () {
    $user = User::factory()->create();

    CaseTt3::query()->create([
        'id' => 1,
        'count' => 1,
        'busted' => 1,
        'dead_flg' => 0,
        'game_datetime' => now()->subDay(),
    ]);

    $url = route('case.records.since', ['game' => 'tt3']).'?'.http_build_query([
        'date' => now()->subDay()->toDateString(),
        'after_id' => 0,
    ]);

    $response = $this->actingAs($user)->getJson($url);

    $response->assertOk();
    $response->assertJson(['records' => []]);
});
