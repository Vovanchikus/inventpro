<?php 
use Winter\User\Facades\Auth;
class Cms697a89a7c0dd9303757981_e8b2dfda12c427dd1c5b7a85fce93199Class extends Cms\Classes\PageCode
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
