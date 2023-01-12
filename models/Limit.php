<?php

namespace app\models;

use yii\base\Model;

class Limit extends Model
{
    /**
     * Метод записывает в сессию время последнего посещения. Если за последнюю минуту было больше 5 запросов, то возвращает число, равное количеству секунд до возобновления доступа, иначе 0 (доступ разрешен).
     * @return int
     */
    public static function check()
    {
        session_start();

        if ( !isset($_SESSION['requests']) ) {
            $_SESSION['requests'] = [];
        }

        $tmpArr = [];
        foreach($_SESSION['requests'] as $request) {
            if ($request > time() - 60) $tmpArr[] = $request;
        }

        $_SESSION['requests'] = $tmpArr;

        if ( count($_SESSION['requests']) >= 5 ) {
            return 60 - (time() - $_SESSION['requests'][0]);
        } else {
            $_SESSION['requests'][] = time();
            return 0;
        }
    }
}
