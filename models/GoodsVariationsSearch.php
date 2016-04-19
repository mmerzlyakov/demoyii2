<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\GoodsVariations;

/**
 * GoodsVariationsSearch represents the model behind the search form about `app\models\GoodsVariations`.
 */
class GoodsVariationsSearch extends GoodsVariations
{
    public $count;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'good_id', 'status'], 'integer'],
            [['code', 'full_name', 'name', 'description','count'], 'safe'],
            [['price', 'comission'], 'number'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = GoodsVariations::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'good_id' => $this->good_id,
            'price' => $this->price,
            'comission' => $this->comission,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'producer_name', $this->producer_name]);

        return $dataProvider;
    }
}
