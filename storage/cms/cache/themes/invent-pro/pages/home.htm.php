<?php 
use Winter\User\Facades\Auth;
class Cms69935c2a1dbbb543030197_00bde93c88c8dfd6f214f302c72d91b5Class extends Cms\Classes\PageCode
{
public function onStart()
{
    if (Auth::check()) {
        // авторизованный пользователь
        return Redirect::to('/warehouse');
    }

    // гость
    return Redirect::to('/login');
}
}
