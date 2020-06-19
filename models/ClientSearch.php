<?php

namespace NIOLAB\oauth2\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use NIOLAB\oauth2\models\Client;

/**
 * ClientSearch represents the model behind the search form of `NIOLAB\oauth2\models\Client`.
 */
class ClientSearch extends Client
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'token_type', 'grant_type', 'created_at', 'updated_at', 'status'], 'integer'],
            [['identifier', 'secret', 'name', 'redirect_uri'], 'safe'],
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
        $query = Client::find();

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
        $query->andFilterWhere([
            'id' => $this->id,
            'token_type' => $this->token_type,
            'grant_type' => $this->grant_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'identifier', $this->identifier])
            ->andFilterWhere(['like', 'secret', $this->secret])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'redirect_uri', $this->redirect_uri]);

        return $dataProvider;
    }
}
