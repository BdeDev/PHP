<?php
/**
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author    : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 *
 */
namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Activity as ActivityModel;

/**
 * Activity represents the model behind the search form about `app\models\Activity`.
 */
class Activity extends ActivityModel
{

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'state_id'
                ],
                'integer'
            ],
            [
                [
                    'title',
                    'description',
                    'created_by_id',
                    'domain_id',
                    'type_id',
                    'created_on'
                ],
                'safe'
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function beforeValidate()
    {
        return true;
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
        $query = ActivityModel::find()->alias('a')->joinWith('createdBy as cb');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        if (! ($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'a.state_id' => $this->state_id,
            'a.domain_id' => $this->domain_id
        ]);

        $query->andFilterWhere([
            'like',
            'a.id',
            $this->id
        ])
            ->andFilterWhere([
            'like',
            'a.title',
            $this->title
        ])
            ->andFilterWhere([
            'like',
            'a.description',
            $this->description
        ])
            ->andFilterWhere([
            'like',
            'a.type_id',
            $this->type_id
        ])
            ->andFilterWhere([
            'like',
            'cb.full_name',
            $this->created_by_id
        ])
            ->andFilterWhere([
            'like',
            'a.created_on',
            $this->created_on
        ]);

        return $dataProvider;
    }
}
