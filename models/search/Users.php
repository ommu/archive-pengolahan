<?php
/**
 * Users
 *
 * Users represents the model behind the search form about `ommu\archivePengolahan\models\Users`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 4 November 2022, 23:33 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archivePengolahan\models\Users as UsersModel;

class Users extends UsersModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['user_id'], 'integer'],
			[['email', 'displayname', 'creation_date', 'lastlogin_date', 'lastlogin_ip'], 'safe'],
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
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $column=null)
	{
        if (!($column && is_array($column))) {
            $query = UsersModel::find()->alias('t');
        } else {
            $query = UsersModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'option option', 
			// 'level.title level', 
			// 'language language', 
			// 'modified modified'
		]);

		$query->groupBy(['user_id']);

        // add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
        // disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);

		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['user_id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('user_id')) {
            unset($params['user_id']);
        }
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
        $query->andFilterWhere([
			't.user_id' => $this->user_id,
			't.level_id' => $this->setting->userlevel_allow_permission,
			'cast(t.creation_date as date)' => $this->creation_date,
			'cast(t.lastlogin_date as date)' => $this->lastlogin_date,
		]);

		$query->andFilterWhere(['like', 't.email', $this->email])
			->andFilterWhere(['like', 't.displayname', $this->displayname])
			->andFilterWhere(['like', 't.lastlogin_ip', $this->lastlogin_ip]);

		return $dataProvider;
	}
}
