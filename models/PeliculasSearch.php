<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Peliculas;

/**
 * PeliculasSearch represents the model behind the search form of `app\models\Peliculas`.
 */
class PeliculasSearch extends Peliculas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['strNombre', 'strGenero', 'strSinopsis', 'strHorario', 'strSala', 'strEstadoPelicula'], 'safe'],
        ];
    }

    public function search($params)
{
    $query = Peliculas::find();

    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => [
            'defaultOrder' => ['id' => SORT_ASC],
        ],
    ]);

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    $query->andFilterWhere(['like', 'strNombre', $this->strNombre])
          ->andFilterWhere(['strGenero' => $this->strGenero])
          ->andFilterWhere(['strHorario' => $this->strHorario]);

    return $dataProvider;
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    
}
