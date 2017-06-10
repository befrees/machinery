<?php
namespace common\modules\store\models\product;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use common\modules\taxonomy\models\TaxonomyItems;
use common\modules\store\models\product\ProductInterface;
use common\modules\store\components\StoreUrlRule;

class ProductSearch extends Model
{
    protected $_model;
    
    public $id;
    public $group;
    public $sku;
    public $title;
    public $source_id;
    public $available;
    public $price;
    public $old_price;
    public $description;
    public $short;
    public $features;

    public function __construct($config = array()) {
        parent::__construct($config);
    }
    
    /**
     * 
     * @return ProductInterface
     */
    public function getModel(){
        return $this->_model;
    }
    
    /**
     * 
     * @param ProductInterface $model
     */
    public function setModel(ProductInterface $model){
        $this->_model = $model;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['source_id', 'available','id'], 'integer'],
            [['price','old_price'], 'number'],
            [['description', 'short', 'features'], 'string'],
            [['sku'], 'string', 'max' => 30],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = $this->_model->find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    /**
     * 
     * @param TaxonomyItems $taxonomyItem
     * @param int $limit
     * @return mixed
     */
    public function getMostRatedId(TaxonomyItems $taxonomyItem, $limit = 5){

        return (new \yii\db\Query())
                        ->select('id')
                        ->from($this->_model->tableName())
                        ->where(['&&', 'index', new \yii\db\Expression('ARRAY['.$taxonomyItem->id.']')])
                        ->distinct()
                        ->limit($limit)
                        ->all();
    }
    
    /**
     * 
     * @param array $ids
     * @return mixed
     */
    public function getProductsByIds(array $ids){
        if(empty($ids)){
            return [];
        }
        return  $this->_model->find()->where(['id' => $ids])
                ->with([
                    'terms',
                    'files',
                    'alias',
                    'groupAlias',
                    'wish',
                    'compare'
                ])->all();
    }
    
    /**
     * 
     * @param string|array $groups
     * @return type
     */
    public function getProductIdsByGroup($groups){
        if(empty($groups)){
            return [];
        }
        return (new \yii\db\Query())
                        ->select(['t0.id'])
                        ->from($this->_model->tableName().' as t0')
                        ->where(['group' => $groups])
                        ->distinct()
                        ->column();
    }

}
