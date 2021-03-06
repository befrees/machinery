<?php

namespace common\modules\store\models\compare;

use Yii;

/**
 * This is the model class for table "compares".
 *
 * @property integer $id
 * @property string $session
 * @property integer $entity_id
 * @property string $model
 */
class Compares extends \yii\db\ActiveRecord
{ 
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'compares';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session', 'entity_id', 'term_id', 'model'], 'required'],
            [['term_id','entity_id'], 'integer'],
            [['session', 'model'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'session' => 'Session',
            'entity_id' => 'Entity ID',
            'model' => 'Model',
        ];
    }

}
