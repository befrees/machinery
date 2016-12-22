<?php

namespace common\modules\store\helpers;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Alias;
use common\helpers\URLify;
use common\helpers\ModelHelper;
use common\modules\taxonomy\helpers\TaxonomyHelper;
use common\modules\store\helpers\ProductHelperInterface;
use common\modules\store\models\ProductInterface;

class ProductHelper implements ProductHelperInterface{
    
    const STATUS_TERMS_VID = 47;
    const TOP_TERM_ID = 1095;

    /**
     * 
     * @param object $entity
     * @return []
     */
    public static function getBreadcrumb($entity){
        $breadcrumb = [];
        $breadcrumb[] = ['label' => 'Каталог', 'url' => '/catalog'];
        $url = '';
        foreach($entity->catalog as $taxonomyItem){
            $url .= '/'.$taxonomyItem->transliteration;
            $breadcrumb[] = ['label' => Html::encode($taxonomyItem->name), 'url' => $url];
        }
        $breadcrumb[] = Html::encode($entity->title);
        return $breadcrumb;
    }
    
    /**
     * 
     * @param mixed $terms
     * @return []
     */
    public static function getStatuses($terms = []){
        if(empty($terms)){
            return [];
        }
        
        $return = [];
        foreach($terms as $term){
            if($term->vid == self::STATUS_TERMS_VID && $term->id != self::TOP_TERM_ID){
                $return[] = $term;
            }
        }
        return $return;
    }
    
    /**
     * 
     * @param mixed $entity
     * @return int
     */
    public function promoPrice($entity){
        if(isset($entity->promoCode)){
            return $entity->price - $entity->promoCode->discount;
        }
        return $entity->price;
    }
    
    /**
     * 
     * @param mixed $entity
     * @return string
     */
    public function shortPattern($entity){
       return '';
    }
    
    /**
     * 
     * @param mixed $entity
     * @return string
     */
    public static function titlePattern($entity){
        $title = [];
        $title[] = $entity->title;
        $title[] = ArrayHelper::getValue($entity->terms, '36.name'); // OC
        $title[] = ArrayHelper::getValue($entity->terms, '31.name'); // color
        $title = array_filter($title);
        return implode(' ', $title);
    }
    
    /**
     * 
     * @param mixed $entity
     * @return string
     */
    public function urlPattern(ProductInterface $entity, Alias $alias){
        $alias->alias = URLify::url($entity->helper->titlePattern($entity)) .'-'. $entity->id;     
        $alias->url = 'store/product?id=' . $entity->id . '&model='. ModelHelper::getModelName($entity);
        $alias->groupAlias = URLify::url($entity->title);
        $alias->groupUrl = 'store/product/group?id=' . $entity->group . '&model='. ModelHelper::getModelName($entity); 
        $alias->groupId = $entity->group;
        
        $link = ArrayHelper::getColumn(TaxonomyHelper::order($entity->catalog), 'transliteration');
        $alias->prefix = implode('/', $link);

        return $alias;
    }
    
    /**
     * 
     * @param [] $attributes
     * @return int
     */
    public static function createGroup($attributes){
        $group = [];
        $group[] = $attributes['model'];
        return crc32(implode(' ', $group));
    }
    
    
}