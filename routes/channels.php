<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin-orders', function ($user) {
    return true; // Tạm thời return true để test, sau này cần kiểm tra role admin
});

Broadcast::channel('orders.admin', function ($user) {
    return $user->is_admin == 1;
});

Broadcast::channel('orders.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
