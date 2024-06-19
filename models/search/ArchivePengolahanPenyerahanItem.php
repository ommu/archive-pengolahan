<?php
/**
 * ArchivePengolahanPenyerahanItem
 *
 * ArchivePengolahanPenyerahanItem represents the model behind the search form about `ommu\archivePengolahan\models\ArchivePengolahanPenyerahanItem`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 14 October 2022, 18:19 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanItem as ArchivePengolahanPenyerahanItemModel;

class ArchivePengolahanPenyerahanItem extends ArchivePengolahanPenyerahanItemModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'penyerahan_id', 'creation_id', 'modified_id', 
                'penyerahanTypeId'], 'integer'],
			[['archive_number', 'archive_description', 'year', 'volume', 'code', 'description', 'creation_date', 'modified_date', 'updated_date', 
                'penyerahanPenciptaArsip', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
            $query = ArchivePengolahanPenyerahanItemModel::find()->alias('t');
        } else {
            $query = ArchivePengolahanPenyerahanItemModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'penyerahan.type penyerahan', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['penyerahanTypeId', '-penyerahanTypeId', 'penyerahanPenciptaArsip', '-penyerahanPenciptaArsip'])) || (
            (isset($params['penyerahanTypeId']) && $params['penyerahanTypeId'] != '') ||
            (isset($params['penyerahanPenciptaArsip']) && $params['penyerahanPenciptaArsip'] != '') ||
            (isset($params['type']) && $params['type'] != '')
        )) {
            $query->joinWith(['penyerahan penyerahan']);
        }
        if (isset($params['sort']) && in_array($params['sort'], ['penyerahanTypeId', '-penyerahanTypeId'])) {
            $query->joinWith(['type type']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['creationDisplayname', '-creationDisplayname'])) || 
            (isset($params['creationDisplayname']) && $params['creationDisplayname'] != '')
        ) {
            $query->joinWith(['creation creation']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['modifiedDisplayname', '-modifiedDisplayname'])) || 
            (isset($params['modifiedDisplayname']) && $params['modifiedDisplayname'] != '')
        ) {
            $query->joinWith(['modified modified']);
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
		$attributes['penyerahanTypeId'] = [
			'asc' => ['type.type_name' => SORT_ASC],
			'desc' => ['type.type_name' => SORT_DESC],
		];
		$attributes['penyerahanPenciptaArsip'] = [
			'asc' => ['penyerahan.kode_box' => SORT_ASC],
			'desc' => ['penyerahan.kode_box' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
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
			't.penyerahan_id' => isset($params['penyerahan']) ? $params['penyerahan'] : $this->penyerahan_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'penyerahan.type_id' => $this->penyerahanTypeId,
		]);

		if ((!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) && !$this->publish) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		$query->andFilterWhere(['like', 't.archive_number', $this->archive_number])
			->andFilterWhere(['like', 't.archive_description', $this->archive_description])
			->andFilterWhere(['like', 't.year', $this->year])
			->andFilterWhere(['like', 't.volume', $this->volume])
			->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.description', $this->description])
			->andFilterWhere(['or', 
                ['like', 'penyerahan.kode_box', $this->penyerahanPenciptaArsip],
                ['like', 'penyerahan.pencipta_arsip', $this->penyerahanPenciptaArsip]
            ])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
