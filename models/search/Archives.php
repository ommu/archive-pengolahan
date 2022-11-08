<?php
/**
 * Archives
 *
 * Archives represents the model behind the search form about `ommu\archivePengolahan\models\Archives`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2022 OMMU (www.ommu.id)
 * @created date 24 October 2022, 17:24 WIB
 * @link https://bitbucket.org/ommu/archive-pengolahan
 *
 */

namespace ommu\archivePengolahan\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\archivePengolahan\models\Archives as ArchivesModel;

class Archives extends ArchivesModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'publish', 'level_id', 'creation_id', 'modified_id', 
                'media', 'preview', 'location', 
                'oFile',
                'rackId', 'roomId', 'depoId', 'buildingId'], 'integer'],
			[['title', 'code', 'medium', 'archive_type', 'archive_date', 'archive_file', 'senarai_file', 'creation_date', 'modified_date', 'updated_date', 
                'creationDisplayname', 'modifiedDisplayname', 'creator', 'repository'], 'safe'],
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
            $query = ArchivesModel::find()->alias('t');
        } else {
            $query = ArchivesModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			// 'grid grid', 
			// 'level.title level', 
			// 'creation creation', 
			// 'modified modified'
		]);
        if ((isset($params['sort']) && in_array($params['sort'], ['oFile', '-oFile'])) || (
            (isset($params['oFile']) && $params['oFile'] != '')
        )) {
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

        // related
        if ((isset($params['sort']) && in_array($params['sort'], ['creator', '-creator'])) || 
            (isset($params['creator']) && $params['creator'] != '')
        ) {
            $query->joinWith(['creators.creator creator']);
        }
        if ((isset($params['sort']) && in_array($params['sort'], ['repository', '-repository'])) || 
            (isset($params['repository']) && $params['repository'] != '')
        ) {
            $query->joinWith(['repositories.repository repository']);
        }
        if (isset($params['media']) && $params['media'] != '') {
            $query->joinWith(['medias medias']);
        }

        // location
        if ((isset($params['location']) && $params['location'] != '') || 
            (isset($params['rackId']) && $params['rackId'] != '') || 
            (isset($params['roomId']) && $params['roomId'] != '') || 
            (isset($params['depoId']) && $params['depoId'] != '') || 
            (isset($params['buildingId']) && $params['buildingId'] != '')
        ) {
            $query->joinWith(['locations locations']);
        }
        if (isset($params['depoId']) && $params['depoId'] != '') {
            $query->joinWith(['locations.room relatedLocationRoom']);
        }
        if (isset($params['buildingId']) && $params['buildingId'] != '') {
            $query->joinWith(['locations.room.parent relatedLocationDepo']);
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
		$attributes['level_id'] = [
			'asc' => ['level.message' => SORT_ASC],
			'desc' => ['level.message' => SORT_DESC],
		];
		$attributes['creationDisplayname'] = [
			'asc' => ['creation.displayname' => SORT_ASC],
			'desc' => ['creation.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['creator'] = [
			'asc' => ['creator.creator_name' => SORT_ASC],
			'desc' => ['creator.creator_name' => SORT_DESC],
		];
		$attributes['repository'] = [
			'asc' => ['repository.repository_name' => SORT_ASC],
			'desc' => ['repository.repository_name' => SORT_DESC],
		];
		$attributes['oFile'] = [
			'asc' => ['grid.luring' => SORT_ASC],
			'desc' => ['grid.luring' => SORT_DESC],
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
			't.level_id' => isset($params['level']) ? $params['level'] : $this->level_id,
			't.archive_type' => $this->archive_type,
			'cast(t.creation_date as date)' => $this->creation_date,
			't.creation_id' => isset($params['creation']) ? $params['creation'] : $this->creation_id,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'medias.media_id' => $this->media,
		]);

        if ($this->isFond) {
            $query->andWhere(['t.level_id' => 1]);
        } else {
            $query->andWhere(['t.level_id' => 8]);
        }

        if (isset($params['preview']) && $params['preview'] != '') {
            if ($this->preview == 1) {
                $query->andWhere(['<>', 't.archive_file', '']);
            } else if ($this->preview == 0) {
                $query->andWhere(['=', 't.archive_file', '']);
            }
        }
        if (isset($params['oFile']) && $params['oFile'] != '') {
            if ($this->oFile == 1) {
                $query->andWhere(['<>', 't.senarai_file', '']);
            } else if ($this->oFile == 0) {
                $query->andWhere(['=', 't.senarai_file', '']);
            }
        }

        // location
		$query->andFilterWhere(['locations.rack_id' => $this->rackId]);
		$query->andFilterWhere(['locations.room_id' => $this->roomId]);
		$query->andFilterWhere(['relatedLocationRoom.parent_id' => $this->depoId]);
		$query->andFilterWhere(['relatedLocationDepo.parent_id' => $this->buildingId]);

        if (isset($params['location']) && $params['location'] != '') {
            if ($this->location == 1) {
                $query->andWhere(['is not', 'locations.id', null]);
            } else if ($this->location == 0) {
                $query->andWhere(['is', 'locations.id', null]);
            }
        }

		if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
            $query->andFilterWhere(['IN', 't.publish', [0,1]]);
        } else {
            $query->andFilterWhere(['t.publish' => $this->publish]);
        }

        if (isset($params['trash']) && $params['trash'] == 1) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        }

		$query->andFilterWhere(['like', 't.title', $this->title])
			->andFilterWhere(['like', 't.code', $this->code])
			->andFilterWhere(['like', 't.medium', $this->medium])
			->andFilterWhere(['like', 't.archive_date', $this->archive_date])
			->andFilterWhere(['like', 't.archive_file', $this->archive_file])
			->andFilterWhere(['like', 't.senarai_file', $this->senarai_file])
			->andFilterWhere(['like', 'creation.displayname', $this->creationDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname])
			->andFilterWhere(['like', 'creator.creator_name', $this->creator])
			->andFilterWhere(['like', 'repository.repository_name', $this->repository]);

		return $dataProvider;
	}
}
