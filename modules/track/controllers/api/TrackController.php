<?php

namespace app\modules\track\controllers\api;

use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\AccessControl;
use app\modules\track\models\Track;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;

class TrackController extends ActiveController
{
    public $modelClass = 'app\modules\track\models\Track';
	
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        
        // Аутентификация
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['index', 'view'],
        ];
			
        // Права доступа
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'view'],
                    'roles' => ['?', '@'],
                ],
                [
                    'allow' => true,
                    'actions' => ['create', 'update', 'delete', 'bulk-update'],
                    'roles' => ['@'],
                ],
            ],
        ];
		
		$behaviors['logger'] = [
			'class' => \yii\filters\VerbFilter::class,
			'actions' => [
				'*' => ['post', 'put', 'get', 'delete'],
			],
		];
        
        return $behaviors;
    }



    public function actions()
    {
        $actions = parent::actions();
        
        // Настройка dataProvider для index action
        $actions['index']['prepareDataProvider'] = function ($action) {
			try {
				$requestParams = Yii::$app->getRequest()->getQueryParams();
				$query = Track::find();
				
				if (isset($requestParams['status'])) {
					$query->andWhere(['status' => $requestParams['status']]);
				}
				
				return new ActiveDataProvider([
					'query' => $query,
					'pagination' => [
						'pageSize' => 20,
					],
					'sort' => [
						'defaultOrder' => [
							'created_at' => SORT_DESC,
						]
					],
				]);
			} catch (\Exception $e) {
				throw new \yii\web\HttpException(500, 'Internal server error');
			}
        };
        
        return $actions;
    }


	/*
	public function actionCreate()
	{
		Yii::info('Raw body: ' . Yii::$app->request->getRawBody(), 'api');
		Yii::info('Parsed JSON: ' . print_r(Yii::$app->request->post(), true), 'api');
	}
	*/

    /**
     * Массовое обновление статусов
     */
    public function actionBulkUpdate()
    {
        $request = Yii::$app->request;
        $trackIds = $request->post('track_ids', []);
        $status = $request->post('status');
        
        if (empty($trackIds) || empty($status)) {
            throw new \yii\web\BadRequestHttpException('track_ids and status are required');
        }
        
        if (!in_array($status, Track::getStatuses())) {
            throw new \yii\web\BadRequestHttpException('Invalid status');
        }
        
        $count = Track::updateAll(
            ['status' => $status],
            ['id' => $trackIds]
        );
        
        return [
            'success' => true,
            'updated' => $count,
        ];
    }
	

    /**
     * Проверка прав доступа
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update', 'delete', 'bulk-update']) && Yii::$app->user->isGuest) {
            throw new ForbiddenHttpException('Вам не разрешено выполнять это действие.');
        }
    }
}