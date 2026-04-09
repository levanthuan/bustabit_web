<?php

test('khách truy cập gốc site bị chuyển tới đăng nhập', function () {
    $response = $this->get('/');

    $response->assertRedirect(route('login'));
});
