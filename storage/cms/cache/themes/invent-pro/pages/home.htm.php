<?php 
use Winter\User\Facades\Auth;
class Cms69c268d9be167265266773_3391da82207993e6f846f65de134c824Class extends Cms\Classes\PageCode
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
