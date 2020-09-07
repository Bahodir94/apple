<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Apples;

/**
 * ApplesSearch represents the model behind the search form of `backend\models\Apples`.
 */
class ApplesSearch extends Apples
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'size'], 'integer'],
            [['color', 'date_of_apperance', 'date_of_fall'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = Apples::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['!=', 'size', 0]);
        $query->andFilterWhere([
            'id' => $this->id,
            'date_of_apperance' => $this->date_of_apperance,
            'date_of_fall' => $this->date_of_fall,
            'status' => $this->status,
            // 'status_of_ate' => $this->status_of_ate,
        ]);

        $query->andFilterWhere(['like', 'color', $this->color]);

        return $dataProvider;
    }
}
