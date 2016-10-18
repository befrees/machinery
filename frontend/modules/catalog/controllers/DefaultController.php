<?php
namespace frontend\modules\catalog\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use frontend\modules\catalog\components\FilterParams;
use common\modules\product\models\ProductRepository;
use common\modules\taxonomy\models\TaxonomyItems;
use common\modules\taxonomy\models\TaxonomyItemsSearch;
use common\modules\taxonomy\helpers\TaxonomyHelper;
use frontend\modules\catalog\helpers\CatalogHelper;
use frontend\modules\catalog\components\Url;

/**
 * Site controller
 */
class DefaultController extends Controller
{
    

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function actionCatalog(){

        $taxonomyItemsSearch = new TaxonomyItemsSearch();
        $models = $taxonomyItemsSearch->getItemsByVid(Yii::$app->params['catalog']['vocabularyId']);
        
        return $this->render('catalog',[
            'menuItems' => TaxonomyHelper::tree($models),
        ]);
    }

    /**
     *
     * @return mixed
     */
    public function actionIndex(Url $filter)
    {   

        if(!$filter->main){
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        
        $searchModel = new ProductRepository(CatalogHelper::getModelByTerm($filter->main));

        if(!$filter->category){
            $childrensTerms = TaxonomyItems::findAll([
                'vid' => $filter->main->vid,
                'pid' => $filter->main->id
            ]); 
            
            if($childrensTerms){
                $items = [];
                foreach($childrensTerms as $childrenTerm){
                    $products = $searchModel->getProducstByIds($searchModel->getCategoryMostRatedItems($childrenTerm));
                    $items[$childrenTerm->id] = [
                        'term' => $childrenTerm,
                        'products' => $products
                    ];
                }
                return $this->render('categories',[
                    'parent' => $filter->main,
                    'items' => $items
                ]);
            }
        }
        $dataProvider = $searchModel->searchItemsByFilter($filter);
        $products = $searchModel->getProducstByIds($dataProvider->getKeys());

        return $this->render('index',[
            'parent' => $filter->main,
            'current' => $filter->category,
            'dataProvider' => $dataProvider,
            'products' => $products,
            'search' => $searchModel,
        ]);
    }

}
