<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Supplier;

/**
 * This is the model class for table "supplier".
 *
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property string $t_status
 */
class SupplierSearch extends \yii\db\ActiveRecord
{
	public static $allStatus = [
		'ok'   => 'OK',
		'hold' => 'HOLD'
	];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplier';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'string'],
            //[['id'], 'unique'],
            [['t_status'], 'string'],
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 3],
            //[['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            't_status' => 'T Status',
        ];
    }
	/*
	 * return $dataProvider
	 */
	public function search($params)
	{
		$query = Supplier::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
            'pagination' => [
                'pageSize' => 6,
            ],
		]);

		if (!($this->load($params) && $this->validate())) {
			return $dataProvider;
		}

		$params = $params['SupplierSearch'];
		if ($params['id']) {
			$query->andFilterWhere([
				'id' => $params['id'],
			]);
		}
		if ($params['t_status']) {
			$query->andFilterWhere([
				//'id'       => $params['id'],
				//'name'     => $params['name'],
				//'code'     => $params['code'],
				't_status' => $params['t_status'],
			]);
		}
		if ($params['name']) {
			$query->andFilterWhere([
				'like', 'name', (string) $params['name'],
			]);
		}
		if ($params['code']) {
			$query->andFilterWhere([
				'like', 'code', (string) $params['code'],
			]);		
		}

		return $dataProvider;
	}
}
