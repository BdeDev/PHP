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
namespace app\models;

use Yii;
use app\models\Feed;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_target_trade".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $state_id
 * @property string $type_id
 * @property string $created_on
 * @property integer $created_by_id
 */
class TargetTrade extends \app\components\TActiveRecord
{

    public function __toString()
    {
        return (string) $this->title;
    }

    const STATE_INACTIVE = 0;

    const STATE_ACTIVE = 1;

    const STATE_DELETED = 2;

    public static function getStateOptions()
    {
        return [
            self::STATE_INACTIVE => "New",
            self::STATE_ACTIVE => "Active",
            self::STATE_DELETED => "Deleted"
        ];
    }

    public function getState()
    {
        $list = self::getStateOptions();
        return isset($list[$this->state_id]) ? $list[$this->state_id] : 'Not Defined';
    }

    public static function getTargetAreaOptions()
    {
        return ArrayHelper::Map(TargetArea::findActive()->all(), 'id', 'title');
    }

    public function getStateBadge()
    {
        $list = [
            self::STATE_INACTIVE => "secondary",
            self::STATE_ACTIVE => "success",
            self::STATE_DELETED => "danger"
        ];
        return isset($list[$this->state_id]) ? \yii\helpers\Html::tag('span', $this->getState(), [
            'class' => 'badge badge-' . $list[$this->state_id]
        ]) : 'Not Defined';
    }

    public static function getActionOptions()
    {
        return [
            self::STATE_INACTIVE => "Deactivate",
            self::STATE_ACTIVE => "Activate",
            self::STATE_DELETED => "Delete"
        ];
    }

    public static function getTypeOptions()
    {
        return [
            "TYPE1",
            "TYPE2",
            "TYPE3"
        ];
    }

    public function getType()
    {
        $list = self::getTypeOptions();
        return isset($list[$this->type_id]) ? $list[$this->type_id] : 'Not Defined';
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            if (empty($this->created_on)) {
                $this->created_on = \date('Y-m-d H:i:s');
            }
            if (empty($this->created_by_id)) {
                $this->created_by_id = self::getCurrentUser();
            }
        } else {}
        return parent::beforeValidate();
    }

    /**
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%target_trade}}';
    }

    /**
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'title',
                    'created_on',
                    'state_id',
                    'created_by_id'
                ],
                'required'
            ],
            [
                [
                    'description'
                ],
                'string'
            ],
            [
                [
                    'state_id',
                    'created_by_id'
                ],
                'integer'
            ],
            [
                [
                    'created_on',
                    'target_area_id'
                ],
                'safe'
            ],
            [
                [
                    'title'
                ],
                'string',
                'max' => 128
            ],
            [
                [
                    'type_id'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'title',
                    'type_id'
                ],
                'trim'
            ],
            [
                [
                    'state_id'
                ],
                'in',
                'range' => array_keys(self::getStateOptions())
            ],
            [
                [
                    'type_id'
                ],
                'in',
                'range' => array_keys(self::getTypeOptions())
            ]
        ];
    }

    /**
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'target_area_id' => Yii::t('app', 'Target Area'),
            'state_id' => Yii::t('app', 'State'),
            'type_id' => Yii::t('app', 'Type'),
            'created_on' => Yii::t('app', 'Created On'),
            'created_by_id' => Yii::t('app', 'Created By')
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), [
            'id' => 'created_by_id'
        ]);
    }

    public static function getHasManyRelations()
    {
        $relations = [];

        $relations['feeds'] = [
            'feeds',
            'Feed',
            'model_id'
        ];
        return $relations;
    }

    public function getTargetArea()
    {
        return $this->hasOne(TargetArea::className(), [
            'id' => 'target_area_id'
        ])->cache();
    }

    public static function getHasOneRelations()
    {
        $relations = [];
        $relations['created_by_id'] = [
            'createdBy',
            'User',
            'id'
        ];
        $relations['target_area_id'] = [
            'target-area',
            'TargetArea',
            'id'
        ];
        return $relations;
    }

    public function beforeDelete()
    {
        if (! parent::beforeDelete()) {
            return false;
        }
        // TODO : start here

        return true;
    }

    public function beforeSave($insert)
    {
        if (! parent::beforeSave($insert)) {
            return false;
        }
        // TODO : start here

        return true;
    }

    public function asJson($with_relations = false)
    {
        $json = [];
        $json['id'] = $this->id;
        $json['title'] = $this->title;
        $json['description'] = $this->description;
        $json['state_id'] = $this->state_id;
        $json['type_id'] = $this->type_id;
        $json['created_on'] = $this->created_on;
        $json['created_by_id'] = $this->created_by_id;
        if ($with_relations) {}
        return $json;
    }

    public static function addTestData($count = 1)
    {
        $faker = \Faker\Factory::create();
        $states = array_keys(self::getStateOptions());
        for ($i = 0; $i < $count; $i ++) {
            $model = new self();
            $model->loadDefaultValues();
            $model->title = $faker->text(10);
            $model->description = $faker->text;
            $model->state_id = $states[rand(0, count($states))];
            $model->type_id = 0;
            $model->save();
        }
    }

    public static function addData($data)
    {
        if (self::find()->count() != 0) {
            return;
        }

        $faker = \Faker\Factory::create();
        foreach ($data as $item) {
            $model = new self();
            $model->loadDefaultValues();

            $model->title = isset($item['title']) ? $item['title'] : $faker->text(10);

            $model->description = isset($item['description']) ? $item['description'] : $faker->text;
            $model->state_id = self::STATE_ACTIVE;

            $model->type_id = isset($item['type_id']) ? $item['type_id'] : 0;
            $model->save();
        }
    }

    public function isAllowed()
    {
        if (User::isAdmin())
            return true;
        if ($this->hasAttribute('created_by_id') && $this->created_by_id == Yii::$app->user->id) {
            return true;
        }

        return User::isUser();
    }

    public function afterSave($insert, $changedAttributes)
    {
        return parent::afterSave($insert, $changedAttributes);
    }
}
