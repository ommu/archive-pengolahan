<?php
/**
 * ArchivePengolahanSchemaCard
 *
 * ArchivePengolahanSchemaCard represents the model behind the search form about `ommu\archivePengolahan\models\ArchivePengolahanSchemaCard`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 9 November 2022, 05:53 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archivePengolahan\models\ArchivePengolahanSchemaCard as ArchivePengolahanSchemaCardModel;

class ArchivePengolahanSchemaCard extends ArchivePengolahanSchemaCardModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'card_id', 'fond_schema_id', 'schema_id', 'creation_date', 'modified_date', 'updated_date', 'cardPenyerahanId', 'schemaTitle', 'finalFondName', 'fondTitle', 'archiveTitle', 'creationDisplayname', 'modifiedDisplayname'], 'safe'],
			[['publish', 'final_id', 'fond_id', 'archive_id', 'creation_id', 'modified_id'], 'integer'],
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
            $query = ArchivePengolahanSchemaCardModel::find()->alias('t');
        } else {
            $query = ArchivePengolahanSchemaCardModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'card.penyerahan.type card', 
			// 'schema schema', 
			// 'final final', 
			// 'fond fond', 
			// 'archive archive', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['cardPenyerahanId', '-cardPenyerahanId'])) || 
            (isset($params['cardPenyerahanId']) && $params['cardPenyerahanId'] != '')
        ) {
            $query->joinWith(['card.penyerahan.type card']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['schemaTitle', '-schemaTitle'])) || 
            (isset($params['schemaTitle']) && $params['schemaTitle'] != '')
        ) {
            $query->joinWith(['schema schema']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['finalFondName', '-finalFondName'])) || 
            (isset($params['finalFondName']) && $params['finalFondName'] != '')
        ) {
            $query->joinWith(['final final']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['fondTitle', '-fondTitle'])) || 
            (isset($params['fondTitle']) && $params['fondTitle'] != '')
        ) {
            $query->joinWith(['fond fond']);
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
		$attributes['cardPenyerahanId'] = [
			'asc' => ['card.type_name' => SORT_ASC],
			'desc' => ['card.type_name' => SORT_DESC],
		];
		$attributes['schemaTitle'] = [
			'asc' => ['schema.title' => SORT_ASC],
			'desc' => ['schema.title' => SORT_DESC],
		];
		$attributes['finalFondName'] = [
			'asc' => ['final.fond_name' => SORT_ASC],
			'desc' => ['final.fond_name' => SORT_DESC],
		];
		$attributes['fondTitle'] = [
			'asc' => ['fond.title' => SORT_ASC],
			'desc' => ['fond.title' => SORT_DESC],
		];
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
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['creation_date' => SORT_DESC],
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
			't.final_id' => isset($params['final']) ? $params['final'] : $this->final_id,
			't.fond_id' => isset($params['fond']) ? $params['fond'] : $this->fond_id,
			't.archive_id' => isset($params['archive']) ? $params['archive'] : $this->archive_id,
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

		$query->andFilterWhere(['like', 't.id', $this->id])
			->andFilterWhere(['like', 't.card_id', $this->card_id])
			->andFilterWhere(['like', 't.fond_schema_id', $this->fond_schema_id])
			->andFilterWhere(['like', 't.schema_id', $this->schema_id])
			->andFilterWhere(['like', 'card.type_name', $this->cardPenyerahanId])
			->andFilterWhere(['like', 'schema.title', $this->schemaTitle])
			->andFilterWhere(['like', 'final.fond_name', $this->finalFondName])
			->andFilterWhere(['like', 'fond.title', $this->fondTitle])
			->andFilterWhere(['like', 'archive.title', $this->archiveTitle])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
