<?php
/**
 * ArchivePengolahanPenyerahan
 *
 * ArchivePengolahanPenyerahan represents the model behind the search form about `ommu\archivePengolahan\models\ArchivePengolahanPenyerahan`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 08:33 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahan as ArchivePengolahanPenyerahanModel;

class ArchivePengolahanPenyerahan extends ArchivePengolahanPenyerahanModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'type_id', 'pengolahan_status', 'creation_id', 'modified_id', 
                'jenisId'], 'integer'],
			[['kode_box', 'pencipta_arsip', 'tahun', 'nomor_arsip', 'jumlah_arsip', 'nomor_box', 'jumlah_box', 'nomor_box_urutan', 'lokasi', 'pengolahan_tahun', 'creation_date', 'modified_date', 'updated_date', 
                'jenisArsip', 'typeName', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
            $query = ArchivePengolahanPenyerahanModel::find()->alias('t');
        } else {
            $query = ArchivePengolahanPenyerahanModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'type type', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['type_id', '-type_id'])) || 
            (isset($params['typeName']) && $params['typeName'] != '')
        ) {
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
        if (isset($params['jenis']) && $params['jenis'] != '') {
            $query->joinWith(['jenis jenis']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['jenisArsip', '-jenisArsip'])) || 
            (isset($params['jenisArsip']) && $params['jenisArsip'] != '')
        ) {
            $query->joinWith(['jenis.tag jenisArsip']);
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
		$attributes['type_id'] = [
			'asc' => ['type.type_name' => SORT_ASC],
			'desc' => ['type.type_name' => SORT_DESC],
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
			't.publish' => $this->publish,
			't.type_id' => isset($params['type']) ? $params['type'] : $this->type_id,
			't.pengolahan_status' => $this->pengolahan_status,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'jenis.tag_id' => $this->jenisId,
		]);

        if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		$query->andFilterWhere(['like', 't.kode_box', $this->kode_box])
			->andFilterWhere(['like', 't.pencipta_arsip', $this->pencipta_arsip])
			->andFilterWhere(['like', 't.tahun', $this->tahun])
			->andFilterWhere(['like', 't.nomor_arsip', $this->nomor_arsip])
			->andFilterWhere(['like', 't.jumlah_arsip', $this->jumlah_arsip])
			->andFilterWhere(['like', 't.nomor_box', $this->nomor_box])
			->andFilterWhere(['like', 't.jumlah_box', $this->jumlah_box])
			->andFilterWhere(['like', 't.nomor_box_urutan', $this->nomor_box_urutan])
			->andFilterWhere(['like', 't.lokasi', $this->lokasi])
			->andFilterWhere(['like', 't.pengolahan_tahun', $this->pengolahan_tahun])
			->andFilterWhere(['like', 'jenisArsip.body', $this->jenisArsip])
			->andFilterWhere(['like', 'type.type_name', $this->typeName])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
