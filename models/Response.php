<?php

namespace app\models;

use yii\base\Model;

class Response extends Model
{
    public static function success($data)
    {
        return json_encode([
            'status_code' => 200,
            'message' => 'ok',
            'data' => json_decode($data, true)
        ]);
    }

    public static function error($message)
    {
        return json_encode([
            'status_code' => 400,
            'message' => $message
        ], JSON_UNESCAPED_UNICODE);
    }

    public static function limit($time)
    {
        return json_encode([
            'status_code' => 500,
            'message' => "Превышено допустимое количество запросов в минуту. Повторите запрос через $time секунд."
        ], JSON_UNESCAPED_UNICODE);
    }
}
