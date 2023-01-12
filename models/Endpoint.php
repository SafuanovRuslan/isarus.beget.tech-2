<?php

namespace app\models;

use Exception;
use yii\db\ActiveRecord;
use app\models\Response;
use app\models\Log;

class Endpoint extends ActiveRecord
{
    public static function tableName()
    {
        return 'endpoint';
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['date', 'data'], 'string'],
        ];
    }

    public static function addPrepared()
    {
        $info = self::getInfo();

        if ( $info['status_code'] == 200 ) {
            $preparedData = self::parseInfo($info);
            return self::savePreparedData($preparedData);
        }
        
        return $info['message'];
    }

    public static function getPrepared($date) 
    {
        Log::write($date);

        if ( preg_match('/\d{4}\-\d{2}-\d{2}/', $date) ) {
            try {
                $response = Endpoint::find()->where(['=', 'date', $date])->limit(1)->asArray()->one();
            } catch (Exception $e) {
                return Response::error($e->getMessage());
            }
            
            if ($response) {
                return Response::success($response['data']);
            }
        }
        return Response::error('Неверный формат даты');
    }

    public static function getInfo()
    {
        $dateFrom = date("d-m-Y", time() - 3600 * 24 * 30);
        $dateTo   = date("d-m-Y");

        try {
            $curl   = curl_init();
                      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                      curl_setopt($curl, CURLOPT_URL, "https://api.apptica.com/package/top_history/1421444/1?date_from=$dateFrom&date_to=$dateTo&B4NKGg=fVN5Q9KVOlOHDx9mOsKPAQsFBlEhBOwguLkNEDTZvKzJzT3l");
                    //   curl_setopt($curl, CURLOPT_URL, "https://erajgiudvurga.ru");
            $result = curl_exec($curl);

            if ( $result ) {
                return json_decode($result, true);
            } else {
                return Response::error('Какие-то проблемы');
            }            
        } catch (Exception $e) {
            return Response::error($e->getMessage());
        }
    }

    public static function parseInfo($info)
    {
        $result = [];

        foreach($info['data'] as $category => $subCategories) {
            foreach($subCategories as $subCategory => $values) {
                foreach($values as $date => $raiting) {
                    if ( isset($result[$date][$category]) ) {
                        $result[$date][$category] = $raiting ? min($result[$date][$category], $raiting) : $result[$date][$category];
                    } else {
                        $result[$date][$category] = $raiting;
                    }
                }               
            }
        }

        return $result;
    }

    public static function savePreparedData($data)
    {
        foreach($data as $date => $raitingInfo) {
            try {
                $model = new Endpoint();
                $model->date = $date;
                $model->data = json_encode($raitingInfo);
                $model->save();
            } catch (Exception $e) {
                $message = $e->getMessage();
                if ( strpos($message, 'Duplicate entry') !== false ) {
                    // Такая запись уже есть в БД, переходим к следующей
                } else {
                    return Response::error($message);
                }
            }
        }
        return true;
    }
}
