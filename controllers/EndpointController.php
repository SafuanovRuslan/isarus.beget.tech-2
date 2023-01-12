<?php

namespace app\controllers;

use app\models\Endpoint;
use app\models\Response;
use app\models\Limit;
use Yii;
use yii\web\Controller;

class EndpointController extends Controller
{
    /**
     * Add app position in top-list into database
     */
    public function actionAddPrepared()
    {
        return Endpoint::addPrepared();
    }

    /**
     * Return prepared info
     */
    public function actionGetPrepared()
    {
        $timeToAccess = Limit::check();
        if ($timeToAccess) return Response::limit($timeToAccess);
        
        $date = Yii::$app->request->get('date');
        return Endpoint::getPrepared($date);
    }
}
