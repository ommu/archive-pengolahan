<?php
/**
 * ArchivePengolahanSchema
 *
 * ArchivePengolahanSchema represents the model behind the search form about `ommu\archivePengolahan\models\ArchivePengolahanSchema`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 8 November 2022, 22:12 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archivePengolahan\models\ArchivePengolahanSchema as ArchivePengolahanSchemaModel;

class ArchivePengolahanSchema extends ArchivePengolahanSchemaModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'parent_id', 'code', 'title', 'creation_date', 'modified_date', 'updated_date', 
                'parentTitle', 'archiveTitle', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
			[['publish', 'archive_id', 'level_id', 'creation_id', 'modified_id',
                'oChild'], 'integer'],
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
            $query = ArchivePengolahanSchemaModel::find()->alias('t');
        } else {
            $query = ArchivePengolahanSchemaModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'archive archive', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if (isset($params['oChild']) && $params['oChild'] != '') {
            $query->joinWith(['childs childs']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['archiveTitle', '-archiveTitle'])) || 
            (isset($params['archiveTitle']) && $params['archiveTitle'] != '')
        ) {
            $query->joinWith(['archive archive']);
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
        if (isset($params['sort']) && in_array($params['sort'], ['level_id', '-level_id']))
        {
            $query->joinWith(['levelTitle levelTitle']);
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
		$attributes['archiveTitle'] = [
			'asc' => ['archive.title' => SORT_ASC],
			'desc' => ['archive.title' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['level_id'] = [
			'asc' => ['levelTitle.message' => SORT_ASC],
			'desc' => ['levelTitle.message' => SORT_DESC],
		];
        if ($this->isFond) {
            $dataProvider->setSort([
                'attributes' => $attributes,
                'defaultOrder' => ['creation_date' => SORT_DESC],
            ]);
        } else {
            $dataProvider->setSort([
                'attributes' => $attributes,
                'defaultOrder' => ['code' => SORT_DESC],
            ]);
        }

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
			't.archive_id' => isset($params['archive']) ? $params['archive'] : $this->archive_id,
			't.level_id' => isset($params['level']) ? $params['level'] : $this->level_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

        if (isset($params['parent']) && (isset($params['parent']) && $params['parent'] != '')) {
            $query->andFilterWhere(['t.parent_id' => isset($params['parent']) ? $params['parent'] : $this->parent_id]);

        } else {
            if ($this->isFond) {
                $query->andWhere(['or',
                    ['=', 't.parent_id', ''],
                    ['is', 't.parent_id', null],
                ]);
            }
        }

        if (isset($params['oChild']) && $params['oChild'] != '') {
            if ($this->oChild == 1) {
                $query->andWhere(['is not', 'childs.id', null]);
            } else if ($this->oChild == 0) {
                $query->andWhere(['is', 'childs.id', null]);
            }
        }

		if ((!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) && !$this->publish) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		$query->andFilterWhere(['like', 't.id', $this->id])
			->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 'archive.title', $this->archiveTitle])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
