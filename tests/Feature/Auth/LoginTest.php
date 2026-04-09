<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('trang đăng nhập hiển thị cho khách', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
});

test('người đã đăng nhập bị chuyển khỏi trang đăng nhập', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('login'));

    $response->assertRedirect(route('home'));
});

test('đăng nhập thành công với email và mật khẩu đúng', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect(route('home'));
});

test('đăng nhập thất bại với mật khẩu sai', function () {
    $user = User::factory()->create();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('đăng xuất thành công', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $this->assertGuest();
    $response->assertRedirect(route('login'));
});

test('khách truy cập trang chủ bị chuyển tới đăng nhập', function () {
    $response = $this->get(route('home'));

    $response->assertRedirect(route('login'));
});
