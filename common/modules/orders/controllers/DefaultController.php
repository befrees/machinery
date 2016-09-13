<?php

namespace common\modules\orders\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\modules\orders\models\Orders;
use common\modules\orders\models\OrdersSearch;

/**
 * ItemsController implements the CRUD actions for TaxonomyItems model.
 */
class DefaultController extends Controller
{
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'delete'],
                        'roles' => ['admin'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['load'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    public function actions()
    {
        return [
            'error' => 'yii\web\ErrorAction',
            'load' => 
            [
                'class' => 'common\modules\orders\widgets\delivery\components\LoadAction',
                'order' => isset(Yii::$app->cart) ? Yii::$app->cart->getOrder() : null,
            ],
        ];
    }
   
    public function actionIndex()
    {
        
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionView($id)
    {
        
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
 
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
