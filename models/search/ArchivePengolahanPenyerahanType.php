<?php
/**
 * ArchivePengolahanPenyerahanType
 *
 * ArchivePengolahanPenyerahanType represents the model behind the search form about `ommu\archivePengolahan\models\ArchivePengolahanPenyerahanType`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 11 October 2022, 07:52 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanType as ArchivePengolahanPenyerahanTypeModel;

class ArchivePengolahanPenyerahanType extends ArchivePengolahanPenyerahanTypeModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'creation_id', 'modified_id', 
                'oPenyerahan'], 'integer'],
			[['type_name', 'type_desc', 'feature', 'creation_date', 'modified_date', 'updated_date', 
                'creationDisplayname', 'modifiedDisplayname'], 'safe'],
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
            $query = ArchivePengolahanPenyerahanTypeModel::find()->alias('t');
        } else {
            $query = ArchivePengolahanPenyerahanTypeModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'grid grid', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['oPenyerahan', '-oPenyerahan'])) || 
            (isset($params['oPenyerahan']) && $params['oPenyerahan'] != '')
        ) {
            $query->joinWith(['grid grid']);
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
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
        $attributes['oPenyerahan'] = [
            'asc' => ['grid.penyerahan' => SORT_ASC],
            'desc' => ['grid.penyerahan' => SORT_DESC],
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
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

        if ((!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) && !$this->publish) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		if (isset($params['oPenyerahan']) && $params['oPenyerahan'] != '') {
            if ($this->oPenyerahan == 1) {
                $query->andWhere(['<>', 'grid.penyerahan', 0]);
            } else if ($this->oPenyerahan == 0) {
                $query->andWhere(['=', 'grid.penyerahan', 0]);
            }
        }
		$query->andFilterWhere(['like', 't.type_name', $this->type_name])
			->andFilterWhere(['like', 't.type_desc', $this->type_desc])
			->andFilterWhere(['like', 't.feature', $this->feature])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
