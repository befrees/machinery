<?php

namespace console\modules\import\controllers;

use Yii;
use yii\console\Controller;
use console\modules\import\models\Sources;
use console\modules\import\models\Validate;

/**
 * ItemsController implements the CRUD actions for TaxonomyItems model.
 */
class DefaultController extends Controller
{
    const TIRES = 500;
    const INSERT_LIMIT  = 3;
    private $terms;

    public function actions()
    {
        return [
        ];
    }
   
    public function actionIndex()
    {
        $sources = Sources::find()->where(['<', 'tires' , self::TIRES])->all();
        $importHelper = Yii::$container->get(\console\modules\import\helpers\ImportHelper::class);
        $validator = Yii::$container->get(\console\modules\import\models\Validate::class); 
        $terms = Yii::$container->get(\console\modules\import\models\Terms::class); 
   
        foreach($sources as $source){
           
            $fileName = \Yii::getAlias('@app')."/../files/import/source_{$source->id}.csv";
            
            if (($handle = fopen($fileName, "r")) === FALSE) {
                $source->addMessage('[1000] Файл не найден или не может быть прочитан.');
                continue;
            }
            
            $data = [];
            $rawData = [];
            $rawTerms = [];
            $fields = [];
            
            while (($line = fgetcsv($handle, 2000, ";")) !== FALSE) {
                if(empty($fields)){
                    $fields = $line;
                    continue;
                }
                
                $line = array_combine($fields, $line);
                
                if(($line = $importHelper->parseTerms($line)) === false){
                    $source->addMessage('[1001] Ошибка парсинга терминов.');
                    continue;
                }

                $validator->setAttributes($line);
                if($validator->validate()){
                    $data[] = $validator->attributes;
                }else{
                    foreach($validator->getErrors() as $field => $errors){
                        foreach($errors as $error){
                           $source->addMessage("[1001] {$field} {$error}"); 
                        }
                    }
                }

                if(count($data) >= self::INSERT_LIMIT){
                    // TODO: bathInsert
                    $data = [];
                }
                
            }
            
            if(count($data)){
                // TODO: bathInsert
                $data = [];
            }
            fclose($handle);
            $source->tires++;
            $source->save();
        }
    }
}
