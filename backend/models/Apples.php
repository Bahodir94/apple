<?php

namespace backend\models;

use Yii;


class Apples extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apples';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color'], 'required'],
            [['date_of_apperance', 'date_of_fall'], 'safe'],
            [['status', 'size'], 'integer'],
            [['color'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'date_of_apperance' => 'Date Of Apperance',
            'date_of_fall' => 'Date Of Fall',
            'status' => 'Status',
            'size' => 'Size',
        ];
    }
}
