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
