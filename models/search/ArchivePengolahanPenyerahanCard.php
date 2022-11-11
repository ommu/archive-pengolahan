<?php
/**
 * ArchivePengolahanPenyerahanCard
 *
 * ArchivePengolahanPenyerahanCard represents the model behind the search form about `ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCard`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 7 November 2022, 11:25 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archivePengolahan\models\ArchivePengolahanPenyerahanCard as ArchivePengolahanPenyerahanCardModel;

class ArchivePengolahanPenyerahanCard extends ArchivePengolahanPenyerahanCardModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'temporary_number', 'archive_description', 'archive_type', 'from_archive_date', 'to_archive_date', 'archive_date', 'medium', 'creation_date', 'modified_date', 'updated_date', 
                'userDisplayname', 'creationDisplayname', 'modifiedDisplayname', 'penyerahanPenciptaArsip', 'subject', 'function'], 'safe'],
			[['publish', 'penyerahan_id', 'user_id', 'creation_id', 'modified_id', 
                'media', 'penyerahanTypeId', 'subjectId', 'functionId'], 'integer'],
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
            $query = ArchivePengolahanPenyerahanCardModel::find()->alias('t');
        } else {
            $query = ArchivePengolahanPenyerahanCardModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'penyerahan.type penyerahan', 
			// 'user user', 
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
        if ((isset($params['sort']) && in_array($params['sort'], ['userDisplayname', '-userDisplayname'])) || 
            (isset($params['userDisplayname']) && $params['userDisplayname'] != '')
        ) {
            $query->joinWith(['user user', 'member member']);
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
        if (isset($params['media']) && $params['media'] != '') {
            $query->joinWith(['medias medias']);
        }
        if (isset($params['subjectId']) && $params['subjectId'] != '') {
            $query->joinWith(['subjects subjects']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['subject', '-subject'])) || 
            (isset($params['subject']) && $params['subject'] != '')
        ) {
            $query->joinWith(['subjects.tag subject']);
        }
        if (isset($params['functionId']) && $params['functionId'] != '') {
            $query->joinWith(['functions functions']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['function', '-function'])) || 
            (isset($params['function']) && $params['function'] != '')
        ) {
            $query->joinWith(['functions.tag function']);
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
		$attributes['userDisplayname'] = [
			'asc' => ['member.displayname' => SORT_ASC],
			'desc' => ['member.displayname' => SORT_DESC],
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
			't.penyerahan_id' => isset($params['penyerahan']) ? $params['penyerahan'] : $this->penyerahan_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.archive_type' => $this->archive_type,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'penyerahan.type_id' => $this->penyerahanTypeId,
			'medias.media_id' => $this->media,
		]);

		$query->andFilterWhere(['subjects.tag_id' => $this->subjectId]);
		$query->andFilterWhere(['functions.tag_id' => $this->functionId]);

		if ((!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) && !$this->publish) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		$query->andFilterWhere(['like', 't.id', $this->id])
			->andFilterWhere(['like', 't.temporary_number', $this->temporary_number])
			->andFilterWhere(['like', 't.archive_description', $this->archive_description])
			->andFilterWhere(['like', 't.from_archive_date', $this->from_archive_date])
			->andFilterWhere(['like', 't.to_archive_date', $this->to_archive_date])
			->andFilterWhere(['like', 't.archive_date', $this->archive_date])
			->andFilterWhere(['like', 't.medium', $this->medium])
			->andFilterWhere(['or', 
                ['like', 'penyerahan.kode_box', $this->penyerahanPenciptaArsip],
                ['like', 'penyerahan.pencipta_arsip', $this->penyerahanPenciptaArsip]
            ])
			->andFilterWhere(['or', 
                ['like', 'user.user_code', $this->userDisplayname],
                ['like', 'member.displayname', $this->userDisplayname]
            ])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname])
			->andFilterWhere(['like', 'subject.body', $this->subject])
			->andFilterWhere(['like', 'function.body', $this->function]);

		return $dataProvider;
	}
}
