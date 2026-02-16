<?php 
use Winter\User\Facades\Auth;
class Cms698201121f650973088718_b40becf3cdf68dc787dd2f9969c210b4Class extends Cms\Classes\PageCode
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
