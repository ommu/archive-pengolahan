<?php
/**
 * ArchivePengolahanImport
 *
 * ArchivePengolahanImport represents the model behind the search form about `ommu\archivePengolahan\models\ArchivePengolahanImport`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 21 October 2022, 06:05 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archivePengolahan\models\ArchivePengolahanImport as ArchivePengolahanImportModel;

class ArchivePengolahanImport extends ArchivePengolahanImportModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'all', 'error', 'rollback', 'creation_id'], 'integer'],
			[['type', 'original_filename', 'custom_filename', 'log', 'creation_date', 
                'creationDisplayname', 'filename'], 'safe'],
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
            $query = ArchivePengolahanImportModel::find()->alias('t');
        } else {
            $query = ArchivePengolahanImportModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'creation creation'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || 
            (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')
        ) {
            $query->joinWith(['creation creation']);
        }

		$query->groupBy(['id']);

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
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['filename'] = [
			'asc' => ['creation.original_filename' => SORT_ASC],
			'desc' => ['creation.original_filename' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
        $query->andFilterWhere([
			't.id' => $this->id,
			't.type' => $this->type,
			't.all' => $this->all,
			't.error' => $this->error,
			't.rollback' => $this->rollback,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
		]);

		$query->andFilterWhere(['like', 't.original_filename', $this->original_filename])
			->andFilterWhere(['like', 't.custom_filename', $this->custom_filename])
			->andFilterWhere(['like', 't.log', $this->log])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['or',
                ['like', 't.original_filename', $this->filename],
                ['like', 't.custom_filename', $this->filename]
            ]);

		return $dataProvider;
	}
}
