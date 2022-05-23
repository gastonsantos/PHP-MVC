<?php

class Navigation {

    public static function redirectTo($url) {
        header('location:' . $url);
        die();
    }
}