<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\Model;

class Log extends Model
{
    public static function write($message)
    {
        $message = '[' . date('H:i:s') . ' ip:' . $_SERVER['REMOTE_ADDR'] . ']: Required date - ' . $message . PHP_EOL;
        try {
            file_put_contents(Yii::getAlias('@log') . '/' . date('d-m-Y') . '.log', $message, FILE_APPEND);
        } catch (Exception $e) {
            mkdir(Yii::getAlias('@log'));
            file_put_contents(Yii::getAlias('@log') . '/' . date('d-m-Y') . '.log', $message, FILE_APPEND);
        }
    }

    public static function logList()
    {
        return array_slice(scandir(Yii::getAlias('@log')), 2);
    }

    public static function read($file)
    {
        return file_get_contents(Yii::getAlias('@log') . '/' . $file);
    }
}
