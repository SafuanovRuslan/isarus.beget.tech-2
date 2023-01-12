<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Log;

class SiteController extends Controller
{
    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionLogs()
    {
        $logs = Log::logList();
        return $this->render('logs', compact('logs'));
    }

    public function actionLog()
    {
        $log = Log::read(Yii::$app->request->get('date'));
        return $this->render('log', compact('log'));
    }
}
